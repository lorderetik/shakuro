<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Operator;
use App\Entity\User;
use App\Form\RefillType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    
    const MIN = 1;
    const MAX = 1000;
    const PHONE_SYMBOLS = 17;
    
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        // get operators
        $repository = $this->getDoctrine()->getRepository(Operator::class);
        $operators = $repository->findAll();
        
        // get operators
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();        
        
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'operators' => $operators,
            'users' => $users
        ]);
    }
    
    /**
     * @Route("/refill", name="app_refill")
     */
    public function refill(Request $request)
    {                
        $operatorId = $request->get('operator_id');
        $operator = null;
        if ($operatorId) {
            $operator = $this->getDoctrine()->getRepository(Operator::class)->find($operatorId);
        }
        $user = $this->getUser();        
        
        $form = $this->createForm(RefillType::class, ['operator' => $operator, 'balance' => $user->balance ?? 0]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $isError = false;
            $operatorId = (int)$request->get('refill')['operator_id'];
            $amount = (int)$request->get('refill')['amount'];
            $phone = str_replace('_', '', $request->get('refill')['phone']);
            
            // check operator
            $operator = $this->getDoctrine()->getRepository(Operator::class)->find($operatorId);
            if (!$operator) {
                $isError = true;
                $form->get('operator_id')->addError(new FormError('Invalid operator ID'));
            }
            
            // check amount
            if ($amount < self::MIN || $amount > self::MAX) {
                $isError = true;
                $form->get('amount')->addError(new FormError("Amount should be in range ".self::MIN ." to ".self::MAX));                
            }
            
            // check phone
            if (strlen($phone) < self::PHONE_SYMBOLS) {
                $isError = true;
                $form->get('phone')->addError(new FormError("Fill the phone"));
            }
            
            if (!$isError) {
                // get all operator users
                $userRepository = $this->getDoctrine()->getRepository(User::class);
                $userRepository->updateBalance($operatorId, $amount);       
                return $this->redirectToRoute("main");
            }
        }
        
        return $this->render('main/refill.html.twig', [
            'controller_name' => 'MainController',
            'refillForm' => $form->createView()
        ]);
    }    
}
