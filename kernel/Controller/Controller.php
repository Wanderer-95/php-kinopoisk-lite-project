<?php

namespace Kernel\Controller;

use Kernel\Http\Redirect\Redirect;
use Kernel\Http\Request\Request;
use Kernel\Session\Session;
use Kernel\View\View;

abstract class Controller
{
    private View $view;
private Request $request;
    private Redirect $redirect;
    private Session $session;

    public function view(string $name): void
    {
        $this->view->render($name);
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Redirect $redirect
     */
    public function setRedirect(Redirect $redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return Redirect
     */
    public function getRedirect(): Redirect
    {
        return $this->redirect;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }
}
