<?php
namespace WeaselUnit\Selenium;

class Driver extends \Selenium\Driver
{

    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    /**
     * Starts a new session.
     *
     * @param string $type     Type of browser
     * @param string $startUrl Start URL for the browser
     */
    public function start($type = '*firefox', $startUrl = 'http://localhost')
    {
        if (null !== $this->sessionId) {
            //throw new Exception("Session already started");
            return;
        }

        $response = $this->doExecute('getNewBrowserSession', $type, $startUrl);

        if (preg_match('/^OK,(.*)$/', $response, $vars)) {
            $this->sessionId = $vars[1];
        } else {
            throw new Exception("Invalid response from server : $response");
        }
    }
}