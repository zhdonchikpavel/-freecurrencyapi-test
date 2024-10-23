<?php
namespace App\Controller;

use App\Form\Type\ExchangeType;
use App\Service\ExchangeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ExchangeRatesController extends AbstractController
{
    public function __construct(private ExchangeService $exchangeService) {}

    #[Route('/')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ExchangeType::class);

        $form->handleRequest($request);

        $exchangeResult = null;
        $sourceCurrencyCode = null;
        $targetCurrencyCode = null;
        $amount = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $sourceCurrency = $form->get('sourceCurrency')->getData();
            $targetCurrency = $form->get('targetCurrency')->getData();
            $amount = $form->get('amount')->getData();
            $sourceCurrencyCode = $sourceCurrency->getCode();
            $targetCurrencyCode = $targetCurrency->getCode();
            $exchangeResult = $this->exchangeService->convert($sourceCurrency, $targetCurrency, $amount);
        }

        return $this->render('exchange-rates/index.html.twig', [
            'form' => $form->createView(),
            'exchangeResult' => $exchangeResult,
            'sourceCurrencyCode' => $sourceCurrencyCode,
            'targetCurrencyCode' => $targetCurrencyCode,
            'amount' => $amount,
        ]);
    }
}