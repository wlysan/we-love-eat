<?php
class ErrorController {
    /**
     * 404 Not Found error page
     */
    public function notFound() {
        http_response_code(404);
        view('error/404');
    }
    
    /**
     * 403 Forbidden error page
     */
    public function forbidden() {
        http_response_code(403);
        view('error/403');
    }
}