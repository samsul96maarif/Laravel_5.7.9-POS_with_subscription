<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */


namespace App\Cores;


use App\Exceptions\AppException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

trait Jsonable
{

    public function safeDecode($input)
    {
        // Fix for PHP's issue with empty objects
        $input = preg_replace('/{\s*}/', "{\"EMPTY_OBJECT\":true}", $input);
        return json_decode($input, true);
    }

    public function safeEncode($input)
    {
        return preg_replace('/{"EMPTY_OBJECT"\s*:\s*true}/', '{}', json_encode($input, JSON_UNESCAPED_SLASHES));
    }

    protected function json($code = 200, $message = '', $data = null, $requestMod = null)
    {

        if (is_null($data)) {
            return response()->json(
                [
                    "code" => $code,
                    "message" => $message
                ], $code, []
            );
        }

        if (method_exists($data, 'items') && !isset($data->items)) {

            if (is_null($requestMod)) {
                $requestMod = $this->requestMod();
            }

            $previousPageUrl = (!empty($data->previousPageUrl())) ?
                $data->previousPageUrl() . $requestMod['query'] :
                $data->previousPageUrl();

            $nextPageUrl = (!empty($data->nextPageUrl())) ?
                $data->nextPageUrl() . $requestMod['query'] :
                $data->nextPageUrl();

            $currentPageUrl = (!empty($data->url($data->currentPage()))) ?
                $data->url($data->currentPage()) . $requestMod['query'] :
                $data->url($data->currentPage());

            $paginate = [
                'has_more_pages' => $data->hasMorePages(),
                'count' => (int)$data->count(),
                'total' => (int)$data->total(),
                'per_page' => (int)$data->perPage(),
                'current_page' => (int)$data->currentPage(),
                'last_page' => (int)$data->lastPage(),
                'prev_page_url' => $previousPageUrl,
                'current_page_url' => $currentPageUrl,
                'next_page_url' => $nextPageUrl
            ];

            return response()->json(
                [
                    "code" => $code,
                    "message" => $message,
                    "data" => $data->items(),
                    "paginate" => $paginate
                ], $code, []
            );
        }

        return response()->json(
            [
                "code" => $code,
                "message" => $message,
                "data" => $data
            ], $code, []
        );

    }

    public function jsonExceptions($data, $code = Response::HTTP_BAD_REQUEST, $message = '')
    {
        $error = null;

        switch (true) {
            case $data instanceof RequestException:

                if (is_null($data->getResponse())) {
                    if (strpos(strtolower($data->getMessage()), "failed to connect"))
                        $error = $this->json(
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            "Failed to connect a service. Connection refused. \nPlease try again.");
                    else
                        $error = $this->json(
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            $data->getMessage());
                } else {
                    $resBody = $this->safeDecode(
                        $data->getResponse()->getBody()
                    );

                    if (isset($resBody['code']) && isset($resBody['message'])) {
                        $error = $this->json(
                            $resBody['code'],
                            $resBody['message'],
                            []);
                    } else
                        $error = $this->json(
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            $resBody,
                            []);
                }

                Log::error($this->json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $data->getMessage()
                ));

                return $error;
                break;

            case $data instanceof AppException:

                $error = $this->json(
                    $data->getCode(),
                    $data->getMessage(),
                    $data->getCause()
                );

                Log::error($error);
                return $error;
                break;

            case $data instanceof QueryException:
                if (env('APP_ENV') == 'production') {
                    $error = $this->json(
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        'Query error exception'
                    );
                } else {
                    $error = $this->json(
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        $data->getMessage()
                    );
                }

                if ($data->getCode() == 23503) {
                    $error = $this->json(
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        'Data already in used. (Constraint Error)'
                    );
                }

                Log::error(json_encode(
                    $data->getCode() .
                    $data->getMessage()
                ));

                return $error;
                break;

            case $data instanceof \Exception:
                $error = $this->json(Response::HTTP_INTERNAL_SERVER_ERROR, $data->getMessage());

                Log::error($error);
                return $error;
                break;

            default:
                $error = $this->json($code, $message, $data);
                Log::error($error);
                return $error;

        }
    }

    function jsonErrors($data, $code = 401, $message = '')
    {
        return $this->jsonExceptions($data, $code, $message);
    }

    function jsonGzSuccess(ResponseInterface $data, $code = 200, $message = '')
    {
        $result = $this->safeDecode($data->getBody());

        if (isset($result['paginate'])) {
            if (isset($result['paginate']['current_page_url'])) {
                $parseCurrentUrl = parse_url($result['paginate']['current_page_url']);
                $result['paginate']['current_page_url'] = url('/') . $parseCurrentUrl['path'] . '?' . $parseCurrentUrl['query'];
            }

            if (isset($result['paginate']['next_page_url'])) {
                $parseNextUrl = parse_url($result['paginate']['next_page_url']);
                $result['paginate']['next_page_url'] = url('/') . $parseNextUrl['path'] . '?' . $parseNextUrl['query'];
            }

            if (isset($result['paginate']['prev_page_url'])) {
                $parsePrevUrl = parse_url($result['paginate']['prev_page_url']);
                $result['paginate']['prev_page_url'] = url('/') . $parsePrevUrl['path'] . '?' . $parsePrevUrl['query'];
            }
        }

        return response()->json($result);
    }

    function jsonGzErrors($data, $code = 401, $message = '')
    {
        switch (true) {
            case $data instanceof RequestException:
                $resBody = $this->safeDecode($data->getResponse()->getBody());

                if (isset($resBody['code']) && isset($resBody['message']))
                    return $this->json(
                        isset($resBody['code']) ? $resBody['code'] : 500,
                        isset($resBody['message']) ? $resBody['message'] : "",
                        isset($resBody['data']) ? $resBody['data'] : []
                    );

                return $this->json($data->getCode(), $resBody, []);
                break;

            case $data instanceof AppException:
                return $this->json($data->getCode(), $data->getMessage(), $data->getCause());
                break;

            case $data instanceof \Exception:
                return $this->json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $data->getMessage(),
                    []
                );
                break;

            default:
                return $this->json($code, $message, $data);

        }
    }
}
