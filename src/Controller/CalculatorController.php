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

  /**
   * @Route("", name="calculator")
   */
  public function index() {
    return $this->render('calculator.html.twig');
  }


  /**
   * @Route("/result", name="calculation_result",  methods={"POST"})
   */
  public function result(Request $request) {
    $expression = $request->get('expression');
    preg_match('/^[0-9\+\-\/\*\.]+$/', $expression, $match);
    if(empty($match)) {
      $msg = 'Incorrect expression (unexpected characters)' . $expression;
    } else {
      try {
        eval("\$result = $expression;");
        $msg = $expression .' = ' . $result;
      }
      catch (\ParseError $e) {
        $msg = 'Incorrect expression: '. $expression;
      }
    }
    return $this->render('result.html.twig', compact('msg'));
  }




}