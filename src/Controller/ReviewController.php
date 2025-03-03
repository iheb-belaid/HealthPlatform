<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReviewController extends AbstractController
{
    #[Route('/review', name: 'app_review')]
    public function index(): Response
    {
        return $this->render('review/index.html.twig');
    }

    #[Route('/review/submit', name: 'app_review_submit', methods: ['POST'])]
    public function submit(Request $request, ReviewRepository $reviewRepository): Response
    {
        $name = $request->request->get('name');
        $content = $request->request->get('content');

        $review = new Review();
        $review->setName($name);
        $review->setContent($content);

        $reviewRepository->save($review, true);

        $this->addFlash('success', 'Thank you for your review!');
        return $this->redirectToRoute('app_review');
    }

    #[Route('/admin/reviews', name: 'app_admin_reviews')]
    public function adminReviews(ReviewRepository $reviewRepository): Response
    {
        $reviews = $reviewRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('review/admin.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/admin/review/delete/{id}', name: 'app_admin_review_delete')]
    public function delete(Review $review, ReviewRepository $reviewRepository): Response
    {
        $reviewRepository->remove($review, true);
        $this->addFlash('success', 'Review deleted successfully.');
        return $this->redirectToRoute('app_admin_reviews');
    }

    #[Route('/review/qr-code', name: 'app_review_qr_code')]
    public function generateQrCode(Request $request): Response
    {
        // Get the current host from the request
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $port = $request->getPort();
        
        // Build the base URL
        $baseUrl = $scheme . '://' . $host;
        if ($port && $port != 80 && $port != 443) {
            $baseUrl .= ':' . $port;
        }
        
        // Generate the full URL
        $url = $baseUrl . $this->generateUrl('app_review');
        
        // Debug the generated URL
        if (isset($_GET['debug'])) {
            return new Response($url);
        }

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($url);

        return new Response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'inline; filename="qr-code.svg"'
        ]);
    }
}
