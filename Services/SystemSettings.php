<?php


namespace Moonshiner\BrigthenBundle\Services\Service;

class SystemSettings
{
    /**
     * Returns the host URL from the system settings
     *
     * @param Request|null $request
     *
     * @return string
     */
    public static function getHostUrl(Request $request = null)
    {
        $request = \Pimcore\Tool::resolveRequest($request);
        $protocol = 'http';
        $hostname = '';
        $port = '';
        if (null !== $request) {
            $protocol = $request->getScheme();
            if (!in_array($request->getPort(), [443, 80])) {
                $port = ':' . $request->getPort();
            }
        }
        // get it from System settings
        $systemConfig = \Pimcore\Config::getSystemConfig()->toArray();
        $hostname = $systemConfig['general']['domain'];
        if (!$hostname) {
            Logger::warn('Couldn\'t determine HTTP Host. No Domain set in "Settings" -> "System" -> "Website" -> "Domain"');
            return '';
        }
        return $protocol . '://' . $hostname . $port;
    }
}
