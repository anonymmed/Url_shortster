<?php

namespace App\Controller;

use App\Entity\Urls;
use App\Service\ShortenerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use phpDocumentor\Reflection\Types\Null_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/api/v1/", name="app_index")
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(['version' => '1']);
    }

    /**
     * @Route("/api/v1/{shortCode}/stats", methods={"GET"})
     * @param $shortCode
     * @param EntityManagerInterface $em
     * @param ShortenerService $service
     * @return JsonResponse
     */
    public function getUrlStats($shortCode, EntityManagerInterface $em, ShortenerService $service): JsonResponse {
        /**
         * @var $check Urls|null
         */
        $check = $em->getRepository(Urls::class)->findOneBy(['shortUrl' => $shortCode]);
        if ($check) {
            $response = $service->getUrlStatsResponse($check);
        } else {
            $response = $service->getErrorResponse("Couldn't find the shortCode");
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/api/v1/createNewUrl", name="app_create_short_url", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ShortenerService $service
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createShortUrl(Request $request, EntityManagerInterface $em, ShortenerService $service):JsonResponse {
        $response = [];
        $data = json_decode($request->getContent(), true);
        $originalUrl = $data['originalUrl'];
        if (!$originalUrl) {
            return new JsonResponse($service->getErrorResponse("Error! No URL has been provided"));
        }
        $shortCode = array_key_exists('shortCode', $data) ? $data['shortCode'] : null;

        if ($shortCode) {
            if (strlen($shortCode) >= 4) {
                $check = $em->getRepository(Urls::class)->findOneBy(['shortUrl' => $shortCode]);
                if (!$check) {
                    $url = $service->createNewShortUrl($originalUrl, $shortCode);
                    $em->persist($url);
                    $em->flush();

                    $response = $service->getSuccessfulResponse(
                        "URL has been generated successfully! Your URL placeholder is: ". $shortCode
                    );
                } else {
                    $response = $service->getErrorResponse("The shortCode $shortCode is already in use!");
                }
            } else {
                $response = $service->getErrorResponse("The shortCode must be greater or equal to 4 characters!");
            }
        } else {
            $check = true;
            while ($check) {
                $randomShortCode = $service->generateRandomAlphaNumericString();
                $checkIfAvailable = $em->getRepository(Urls::class)->findOneBy(['shortUrl' => $randomShortCode]);
                if (!$checkIfAvailable) {
                    $url = $service->createNewShortUrl($originalUrl, $randomShortCode);
                    $em->persist($url);
                    $em->flush();

                    $response = $service->getSuccessfulResponse(
                        "Short URL has been generated successfully! Your URL placeholder is: ". $randomShortCode
                    );
                    $check = false;
                }
            }
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/{shortCode}", methods={"GET"})
     * @param string $shortCode
     * @param EntityManagerInterface $em
     * @param ShortenerService $service
     * @return RedirectResponse | JsonResponse
     */
    public function redirectToOrigin(string $shortCode, EntityManagerInterface $em, ShortenerService $service) {
        /**
         * @var $check Urls|null
         */
        $check = $em->getRepository(Urls::class)->findOneBy(['shortUrl' => $shortCode]);
        if($check) {
            $check->setLastVisited(new \DateTime('NOW'));
            $check->setVisitCount($check->getVisitCount() + 1);
            $em->flush();
            return $this->redirect($check->getOriginalUrl());
        } else {
            return new JsonResponse($service->getErrorResponse("Shortcode was not found!"));
        }
    }
}
