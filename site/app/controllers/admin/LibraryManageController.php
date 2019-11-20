<?php namespace app\controllers\admin;


use app\libraries\Core;
use app\libraries\FileUtils;
use app\libraries\response\Response;
use app\controllers\AbstractController;
use app\exceptions\NotEnabledException;
use app\libraries\response\WebResponse;
use app\libraries\response\JsonResponse;
use app\libraries\routers\AccessControl;
use Symfony\Component\Routing\Annotation\Route;
use app\libraries\homework\UseCases\LibraryAddUseCase;
use app\libraries\homework\UseCases\LibraryGetUseCase;
use app\libraries\homework\UseCases\LibraryRemoveUseCase;

/**
 * Class LibraryManage
 *
 * Following the clean architecture
 * https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html
 *
 * @package app\controllers\admin
 */
class LibraryManageController extends AbstractController {
    /**
     * LibraryManage constructor.
     * @param Core $core
     * @throws NotEnabledException
     */
    public function __construct(Core $core) {
        parent::__construct($core);

        if (!$this->core->getConfig()->useHomeworkLibrary()) {
            throw new NotEnabledException();
        }
    }

    /**
     * Controller route to show the homework library page.
     *
     * @Route("/homework/library/manage", methods={"GET"})
     * @return Response
     */
    public function showLibraryManagePage() {
        $useCase = new LibraryGetUseCase($this->core);

        $response = $useCase->getLibraries();

        return Response::WebOnlyResponse(
            new WebResponse([
                'admin', 'LibraryManager'
            ], 'showLibraryManager',
                'Do all your fancy homework library things here!',
                $response->getResults()
            )
        );
    }

    /**
     * Function for uploading a zipped up library to the server. This should be called via AJAX, saving the result
     * to the json_buffer of the Output object, return a true or false on whether or not it succeeded.
     *
     * @Route("/homework/library/manage/upload/zip", methods={"POST"})
     * @return Response
     */
    public function ajaxUploadLibraryFromZip(): Response {
        $useCase = new LibraryAddUseCase($this->core);

        $fileInfo = $_FILES['zip'] ?? null;

        $results = $useCase->addZipLibrary($fileInfo);

        if ($fileInfo && isset($fileInfo['tmp_name'])) {
            FileUtils::rmFile($fileInfo['tmp_name']);
        }

        if ($results->error) {
            $response = JsonResponse::getFailResponse($results->error);
        } else {
            $response = JsonResponse::getSuccessResponse($results->getMessage());
        }

        return Response::JsonOnlyResponse($response);
    }

    /**
     * Function for adding a homework library to the server from a git repository. This should be called via AJAX,
     * saving the result to the json_buffer of the Output object, returns a true or false on whether or not it
     * succeeded.
     *
     * @Route("/homework/library/manage/upload/git", methods={"POST"})
     * @return Response
     */
    public function ajaxUploadLibraryFromGit(): Response {
        $useCase = new LibraryAddUseCase($this->core);

        $results = $useCase->addGitLibrary($_POST['git_url'] ?? null);

        if ($results->error) {
            $response = JsonResponse::getFailResponse($results->error);
        } else {
            $response = JsonResponse::getSuccessResponse($results->getMessage());
        }

        return Response::JsonOnlyResponse($response);
    }

    /**
     * Function for returning all libraries stored on the system. This should be called via AJAX
     * saving the result to the json_buffer of the Output object, returns a true or false on
     * whether or not it succeeded.
     *
     * @Route("/homework/library/manage/list", methods={"GET"})
     * @return Response
     */
    public function ajaxGetLibraryList(): Response {
        $useCase = new LibraryGetUseCase($this->core);

        $results = $useCase->getLibraries();

        return Response::JsonOnlyResponse(
            JsonResponse::getSuccessResponse($results->getResults())
        );
    }

    /**
     * Function for deleting a specific library stored on the system. This should be called via
     * a DELETE AJAX request. It then returns json data to the caller about the request specifying
     * if it was successful or not and any error messages.
     *
     * @Route("/homework/library/manage/remove/{name}", methods={"DELETE"})
     * @param string $name
     * @return Response
     */
    public function ajaxRemoveLibrary(string $name): Response {
        $useCase = new LibraryRemoveUseCase($this->core);

        $results = $useCase->removeLibrary($name);

        if ($results->error) {
            $response = JsonResponse::getFailResponse($results->error);
        } else {
            $response = JsonResponse::getSuccessResponse($results->getMessage());
        }

        return Response::JsonOnlyResponse($response);
    }
}
