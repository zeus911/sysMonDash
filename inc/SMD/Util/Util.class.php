<?php
/**
 * sysMonDash
 *
 * @author    nuxsmin
 * @link      http://cygnux.org
 * @copyright 2012-2016 Rubén Domínguez nuxsmin@cygnux.org
 *
 * This file is part of sysMonDash.
 *
 * sysMonDash is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysMonDash is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with sysMonDash.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace SMD\Util;
use SMD\Core\Language;
use SMD\Core\Session;

/**
 * Class Util
 * @package SMD\Util
 */
class Util
{
    /**
     * Ordenar un array por una clave dada.
     *
     * @param array $data El array a ordenar
     * @param string $fieldName La clave de ordenación
     * @param bool $sortAsc El orden de ordenación
     * @return mixed
     */
    public static function arraySortByKey(array &$data, $fieldName, $sortAsc = true)
    {
        // Ordenar el array multidimensional por la clave $fielName de mayor a menor
        usort($data, function ($a, $b) use ($fieldName, $sortAsc) {
            return ($sortAsc) ? ($a[$fieldName] < $b[$fieldName]) :($a[$fieldName] > $b[$fieldName]);
        });

        return $data;
    }

    /**
     * Función para calcular el tiempo transcurrido
     *
     * @param int $secs El tiempo en formato UNIX
     * @return string Cadena con las hora:minutos:segundos
     */
    public static function timeElapsed($secs)
    {
        $bit = array(
//        'a' => $secs / 31556926 % 12,
//        'w' => $secs / 604800 % 52,
            'd' => abs($secs) / 86400 % 365,
            'h' => abs($secs) / 3600 % 24,
            'm' => abs($secs) / 60 % 60,
            's' => abs($secs) % 60
        );

        foreach ($bit as $k => $v) {
            if ($v > 0) {
                $ret[] = $v . $k;
            }
        }

        return join(' ', $ret);
    }

    /**
     * Comprobar si es necesario reiniciar la página para actualizar
     *
     * @return bool
     */
    public static function checkRefreshSession()
    {
        $version = 1;

        if (session_status() === PHP_SESSION_ACTIVE
            && (!isset($_SESSION['EXPIRE'], $_SESSION['VERSION'])
            || $_SESSION['EXPIRE'] - time() < 0
            || $_SESSION['VERSION'] < $version)
        ) {
            $_SESSION['VERSION'] = $version;
            $_SESSION['EXPIRE'] = time() + 7200;
            Session::setCssHash(self::getCssHash());
            return true;
        }

        return false;
    }

    /**
     * Info de la aplicación
     *
     * @return array
     */
    public static function getAppInfo()
    {
        return array(
            'appCode' => '<a href="https://github.com/nuxsmin/sysMonDash" target="_blank" title="sysMonDash - GitHub">sysMonDash</a>',
            'appAuthor' => '<a href="http://cygnux.org" target="_blank" title="' . Language::t('Un proyecto de cygnux.org') . '">cygnux.org</a>'
        );
    }

    /**
     * Devuelve el hash del archivo CSS
     *
     * @return string
     */
    public static function getCssHash()
    {
        return hash_file('md5', APP_ROOT . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'styles.css');
    }
}