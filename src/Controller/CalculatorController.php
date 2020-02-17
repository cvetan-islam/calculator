<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calculator")
 */
class CalculatorController extends AbstractController{

  private $expression = 0;
  private $msg = null;

  /**
   * @Route("", name="calculator")
   */
  public function index() {
    return $this->render('calculator.html.twig');
  }


  /**
   * @Route("/result", name="calculation_result",  methods={"POST"})
   */
//  public function result(Request $request) {
//    $expression = $request->get('expression');
//    preg_match('/^[0-9\+\-\/\*]+$/', $expression, $match);
//    if(empty($match)) {
//      $msg = 'Incorrect expression (unexpected characters)' . $expression;
//    } else {
//      try {
//        eval("\$result = $expression;");
//        $msg = $expression .' = ' . $result;
//      }
//      catch (\ParseError $e) {
//        $msg = 'Incorrect expression: '. $expression;
//      }
//    }
//    return $this->render('result.html.twig', compact('msg'));
//  }


  public function result(Request $request) {
    $this->expression = $request->get('expression');
    $msg = $this->expression .' = ';

    $this->checkPriority();

    $msg .= $this->msg ?? $this->expression;
    return $this->render('result.html.twig', compact('msg'));

  }


  /**
   * @return void|null
   */
  private function checkPriority() {
    if($this->msg)
      return $this->msg;

    $this->expression = str_replace('--', '+', $this->expression);
    $expression = $this->expression;

    $posMulti = strpos($expression, '*');
    $posDivide = strpos($expression, '/');
    $posPlus = strpos($expression, '+');
    $posMinus = strpos($expression, '-');
    if($posMinus === 0) { //fist position should be skipped for '-'
      $posMinus = strpos(substr($expression,1), '-');
    }

    if($posMulti !== false) {
      if($posDivide !== false && $posDivide < $posMulti) {
         return $this->divide();
        }
      return $this->multiply();
    }


    if($posDivide !== false) {
      return $this->divide();
    }

    if($posPlus !== false) {
      if($posMinus !== false && $posMinus !=0 && $posMinus < $posPlus) {
         return $this->subtract();
        }
      return $this->add();
    }

    if($posMinus !== false) {
      return $this->subtract();
    }

  }


  /**
   * @return void|null
   */
  private function multiply() {
    $expression = $this->expression;
    preg_match('/[0-9]+(\.[0-9]+)?\*(\-)?[0-9]+(\.[0-9]+)?/', $expression,$matches);
    if(!isset($matches[0])) {
      $this->msg = 'Incorrect expression!';
      return;
    }

    $expl = explode('*', $matches[0]);
    $res = $expl[0] * $expl[1];

    $pos = strpos($expression, $matches[0]);
    $this->expression = substr($expression, 0, $pos) . $res . substr($expression,$pos + strlen($matches[0]));
    return $this->checkPriority();

  }

  /**
   * @return void|null
   */
  private function divide() {
    $expression = $this->expression;
    preg_match('/[0-9]+(\.[0-9]+)?\/(\-)?[0-9]+(\.[0-9]+)?/', $expression,$matches);
    if(!isset($matches[0])){
      $this->msg = 'Incorrect expression!';
      return;
    }

    $expl = explode('/', $matches[0]);
    $res = $expl[0] / $expl[1];

    $pos = strpos($expression, $matches[0]);
    $this->expression = substr($expression, 0, $pos) . $res . substr($expression,$pos + strlen($matches[0]));
    return $this->checkPriority();
  }

  /**
   * @return void|null
   */
  private function add() {
    $expression = $this->expression;
    preg_match('/(-)?[0-9]+(\.[0-9]+)?\+(\-)?[0-9]+(\.[0-9]+)?/', $expression,$matches);
    if(!isset($matches[0])) {
      $this->msg = 'Incorrect expression!';
      return;
    }

    $expl = explode('+', $matches[0]);
    $res = $expl[0] + $expl[1];

    $pos = strpos($expression, $matches[0]);
    $this->expression = substr($expression, 0, $pos) . $res . substr($expression,$pos + strlen($matches[0]));
    return $this->checkPriority();
  }

  /**
   * @return void|null
   */
  private function subtract() {
    $expression = $this->expression;
    preg_match('/(-)?[0-9]+(\.[0-9]+)?\-(\-)?[0-9]+(\.[0-9]+)?/', $expression,$matches);
    if(!isset($matches[0])){
      $this->msg = 'Incorrect expression!';
      return;
    }

    if($matches[0][0] === '-') {
      $new_matches[0] = substr($matches[0],1);
      $expl = explode('-', $new_matches[0]);
      $res =  ($expl[0] + $expl[1]) * (-1);
    } else {
      $expl = explode('-', $matches[0]);
      $res = $expl[0] - $expl[1];
    }

    $pos = strpos($expression, $matches[0]);
    $this->expression = substr($expression, 0, $pos) . $res . substr($expression,$pos + strlen($matches[0]));

    return $this->checkPriority();
  }

}