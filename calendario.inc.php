<?php

/**
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 *         Clase para generar calendario html
 *
 * @author William J Sanders
 *
 *         Reprogramacion
 * @author Jose-Manuel Contardo
 *
 *         Añadido manejo Ajax y documentacion
 *         Añadido calendario anual para revisiones qnova
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */


class BaseCalendar
{

    private $calLinkto = "http://www.yourdomain.com/folder";
    private $calBorder = "0";
    private $calWidth = "100%";
    private $calBGCellColor = "#FFA748";
    private $calBGCellToday = "#0088FF";
    private $calTextColor = "#46154F";
    private $campo;

    /**
     *         Constructor
     *
     * @access public
     * @param String $sCampo
     *
     */
    function __construct($sCampo)
    {
        $this->campo = $sCampo;
    }

    /**
     * Funcion para obtener el nombre corto del mes traducido
     * @param $month
     * @return mixed
     */
    public function MonthShort($month)
    {

        switch ($month) {
            case "Jan":
                return gettext('sEn');
                break;
            case "Apr":
                return gettext('sAbr');
                break;
            case "Aug":
                return gettext('sAgo');
                break;
            case "Dec":
                return gettext('sDic');
                break;
            default:
                return $month;
        }
    }

    /**
     * Funcion para obtener el nombre largo del mes
     * @param $num_month
     * @return mixed
     */
    public function MonthFull($num_month)
    {

        $calMes["01"] = gettext('sEnero');
        $calMes["02"] = gettext('sFebrero');
        $calMes["03"] = gettext('sMarzo');
        $calMes["04"] = gettext('sAbril');
        $calMes["05"] = gettext('sMayo');
        $calMes["06"] = gettext('sJunio');
        $calMes["07"] = gettext('sJulio');
        $calMes["08"] = gettext('sAgosto');
        $calMes["09"] = gettext('sSeptiembre');
        $calMes["10"] = gettext('sOctubre');
        $calMes["11"] = gettext('sNoviembre');
        $calMes["12"] = gettext('sDiciembre');

        return $calMes[$num_month];
    }


    /**
     * Funcion que muestra el calendario del mes que le pasamos, 0 corresponde al mes actual
     * @param int $shift
     * @return string
     */

    function displayMonth($shift = 0)
    {
        $sHtml='';

//Assign timestamps to dates
        $today_ts = mktime(0, 0, 0, date("n"), date("d"), date("Y")); // non relative date
        $firstday_month_ts = mktime(0, 0, 0, date("n") + $shift, 1, date("Y")); // first day of the month
        $lastday_month_ts = mktime(0, 0, 0, date("n") + $shift + 1, 0, date("Y"));    // last day of the month

//Assign numbers and text to the month, year and day
        $numYear = date("Y", $firstday_month_ts);
        $numMonth = date("m", $firstday_month_ts);
        $textMonth = $this->MonthShort(date("M", $firstday_month_ts));
        $daysInMonth = date("t", $firstday_month_ts);

// raplace day 0 for day 7, week starts on monday
        $dayMonth_start = date("w", $firstday_month_ts);
        if ($dayMonth_start == 0) {
            $dayMonth_start = 7;
        }


        $dayMonth_end = date("w", $lastday_month_ts);
        if ($dayMonth_end == 0) {
            $dayMonth_end = 7;
        }


// formating output as a table
        $sHtml .= "<table class=\"mes\" border=" . ($this->calBorder) . " cellspacing=3 cellpadding=3 width=" . ($this->calWidth) . "\">\n";
        $sHtml .= "<caption class=\"nombre_mes\">" . $textMonth . "&nbsp;&nbsp;" . $numYear . "</caption>\n";
        $sHtml .= "<tr bgcolor=" . ($this->calBGCellColor) . ">\n";

// Day headers, starting from Lunes (monday)
        $sHtml .= "<th>" . gettext('sLunes') . "</th><th>" . gettext('sMartes') . "</th><th>".
            gettext('sMiercoles') . "</th><th>" . gettext('sJueves') . "</th><th>" . gettext('sViernes').
            "</th><th>".gettext('sSabado')."</th><th>".gettext('sDomingo')."</th></tr>\n";
        $sHtml .= "<tr>\n";

// Fill with white spaces until the first day
        for ($k = 1; $k < $dayMonth_start; $k++) {
            $sHtml .= "<td>&nbsp;</td>\n";
        }
// 
        for ($i = 1; $i <= $daysInMonth; $i++) {

            // Assigns a timestamp to day i
            $day_i_ts = mktime(0, 0, 0, date("n", $firstday_month_ts), $i, date("Y", $firstday_month_ts));
            $day_i = date("w", $day_i_ts);

            //Placing Domingo (Sunday) as last day of the week
            if ($day_i == 0) {
                $day_i = 7;
            }

            // Target link. You should replace this with some function(i)
            $d2_i = date("d", $day_i_ts);
            $link_i = "<b onclick=\"rellenaMes(" . $numYear . "," . $numMonth . ", " . $d2_i . ", '".
                $this->campo."')\">".($i) . "</b>";

            // Plancing day i on calendar

            if ($shift == 0 && $today_ts == $day_i_ts) {
                $sHtml .= "<td align=\"center\" bgcolor=" . ($this->calBGCellToday) . "><strong>" . $link_i . "</strong></td>";
            } else {
                $sHtml .= "<td align=\"center\" bgcolor=" . ($this->calBGCellColor) . ">" . $link_i . "></td>\n";
            }
            if ($day_i == 7 && $i < $daysInMonth) {
                $sHtml .= "</tr><tr>\n";
            } else if ($day_i == 7 && $i == $daysInMonth) {
                $sHtml .= "</tr>\n";
            } else if ($i == $daysInMonth) {
                for ($h = $dayMonth_end; $h < 7; $h++) {
                    $sHtml .= "<td>&nbsp;</td>\n";
                }
                $sHtml .= "</tr>\n";
            }

        } // end for

        $sHtml .= "</table>\n";
        return $sHtml;
    } // end function
} // end of class


class AnualCalendar
{
    private $iAgno;
    private $calBorder = "0";
    private $calWidth = "100%";
    private $calBGCellColor = "#FFA748";    // #6699CC";
    private $calBGCellToday = "#0088FF";                // #66ff66";
    private $calBGCellRevision = "#F718FF";        //#898988";
    private $calTextColor = "#000000";
    private $campo;
    private $aFechas;
    private $sAccion;

    function __construct($iAgno, $sAccion, $aFechas)
    {
        $this->sAccion = $sAccion;
        $this->iAgno = $iAgno;
        $this->aFechas = $aFechas;
    }

    /**
     * Funcion para obtener el nombre corto del mes traducido
     * @param $month
     * @return mixed
     */
    public function MonthShort($month)
    {

        switch ($month) {
            case "Jan":
                return gettext('sEn');
                break;
            case "Apr":
                return gettext('sAbr');
                break;
            case "Aug":
                return gettext('sAgo');
                break;
            case "Dec":
                return gettext('sDic');
                break;
            default:
                return $month;
        }
    }

    /**
     * Funcion para obtener el nombre largo del mes
     * @param $num_month
     * @return mixed
     */
    public function MonthFull($num_month)
    {
        $calMes["01"] = gettext('sEnero');
        $calMes["02"] = gettext('sFebrero');
        $calMes["03"] = gettext('sMarzo');
        $calMes["04"] = gettext('sAbril');
        $calMes["05"] = gettext('sMayo');
        $calMes["06"] = gettext('sJunio');
        $calMes["07"] = gettext('sJulio');
        $calMes["08"] = gettext('sAgosto');
        $calMes["09"] = gettext('sSeptiembre');
        $calMes["10"] = gettext('sOctubre');
        $calMes["11"] = gettext('sNoviembre');
        $calMes["12"] = gettext('sDiciembre');

        return $calMes[$num_month];
    }


    function displayAgno()
    {
        $mes = getdate();
        $sHtml = "<div id=\"cal_grande\" align=\"center\" class=\"anual\">";
        $sHtml .= "<b onMouseOver=\"this.className='encima'\" onclick=\"sndReq('" . $this->sAccion . "','',1,".
            ($this->iAgno - 1) . ")\">" .gettext('sIzquierda')."</b>&nbsp&nbsp"; //Flechas
        $sHtml .= "<b class=\"agno\">" . $this->iAgno . "</b>&nbsp&nbsp";
        $sHtml .= "<b onMouseOver=\"this.className='encima'\" onclick=\"sndReq('" . $this->sAccion . "','',1,".
            ($this->iAgno + 1) . ")\">".gettext('sDerecha')."</b>&nbsp&nbsp";
        $sHtml .= "<br /><table class=\"dias\"><tr valign=\"top\">"; // flechaaaas
        $iColumnas = 0;
        $empiezo = 13 - $mes['mon'] + (12 * ($this->iAgno - $mes['year'] - 1));
        for ($i = $empiezo; $i <= ($empiezo + 11); $i++) {
            $sHtml .= "<td>" . $this->displayMonth($i) . "</td>";
            $iColumnas++;
            if (($iColumnas % 4) == 0) {
                $sHtml .= "</tr><tr valign=\"top\">";
            }
        }
        $sHtml .= "</tr></table></div>";
        return $sHtml;
    }


    function displayMonth($shift = 0)
    {
        $sHtml='';
//Assign timestamps to dates
        $today_ts = mktime(0, 0, 0, date("n"), date("d"), date("Y")); // non relative date
        $firstday_month_ts = mktime(0, 0, 0, date("n") + $shift, 1, date("Y")); // first day of the month
        $lastday_month_ts = mktime(0, 0, 0, date("n") + $shift + 1, 0, date("Y"));    // last day of the month

//Assign numbers and text to the month, year and day
        $numYear = date("Y", $firstday_month_ts);
        $numMonth = date("m", $firstday_month_ts);
        $textMonth = $this->MonthShort(date("M", $firstday_month_ts));
        $daysInMonth = date("t", $firstday_month_ts);

// raplace day 0 for day 7, week starts on monday
        $dayMonth_start = date("w", $firstday_month_ts);
        if ($dayMonth_start == 0) {
            $dayMonth_start = 7;
        }


        $dayMonth_end = date("w", $lastday_month_ts);
        if ($dayMonth_end == 0) {
            $dayMonth_end = 7;
        }


// formating output as a table
        $sHtml .= "<table class=\"calendar\" border=" . ($this->calBorder) . " cellspacing=3 cellpadding=3 width=" . ($this->calWidth) . "\">\n";
        $sHtml .= "<caption><center>" . $textMonth . "&nbsp;&nbsp;" . $numYear . "</center></caption>\n";
        $sHtml .= "<tr bgcolor=" . ($this->calBGCellColor) . ">\n";

// Day headers, starting from Lunes (monday)
        $sHtml .= "<th>" . gettext('sLunes') . "</th><th>" . gettext('sMartes') . "</th><th>".
            gettext('sMiercoles') . "</th><th>" . gettext('sJueves') . "</th><th>" . gettext('sViernes').
            "</th><th>".gettext('sSabado')."</th><th>".gettext('sDomingo')."</th></tr>\n";
        $sHtml .= "<tr>\n";

// Fill with white spaces until the first day
        for ($k = 1; $k < $dayMonth_start; $k++) {
            $sHtml .= "<td>&nbsp;</td>\n";
        }
// 
        for ($i = 1; $i <= $daysInMonth; $i++) {

            // Assigns a timestamp to day i
            $day_i_ts = mktime(0, 0, 0, date("n", $firstday_month_ts), $i, date("Y", $firstday_month_ts));
            $day_i = date("w", $day_i_ts);
            //Placing Domingo (Sunday) as last day of the week
            if ($day_i == 0) {
                $day_i = 7;
            }

            // Plancing day i on calendar

            if ($shift == 0 && $today_ts == $day_i_ts) {
                $link_i = "<b onMouseOver=\"this.className='encima_casilla'\">" . ($i) . "</b>";
                $sHtml .= "<td align=\"center\" bgcolor=" . ($this->calBGCellToday) . "><strong>".$link_i."</strong></td>";
            } else if ($this->hay_Revision($day_i_ts)) {
                $link_i = "<b onclick=\"sndReq('equipos:planmantenimientoid:listado:nuevo','',1,'" . $this->aFechas[$day_i_ts][0].
                    "')\", title='" . $this->aFechas[$day_i_ts][1] . "'>" . $i . "</b>";
                $sHtml .= "<td align=\"center\" onMouseOver=\"this.className='encima_casilla'\" bgcolor=".
                    ($this->calBGCellRevision) . "><strong>".$link_i."</strong></td>";

            } else {
                $link_i = "<b>" . ($i) . "</b>";
                $sHtml .= "<td align=\"center\" bgcolor=".($this->calBGCellColor).">".$link_i."</td>\n";
            }
            if ($day_i == 7 && $i < $daysInMonth) {
                $sHtml .= "</tr><tr>\n";
            } else if ($day_i == 7 && $i == $daysInMonth) {
                $sHtml .= "</tr>\n";
            } else if ($i == $daysInMonth) {
                for ($h = $dayMonth_end; $h < 7; $h++) {
                    $sHtml .= "<td>&nbsp;</td>\n";
                }
                $sHtml .= "</tr>\n";
            }
        } // end for
        $sHtml .= "</table>\n";
        return $sHtml;
    } // end function


    /**
     * comparar aqui con todos los dias que nos pasaron por parametro apra ver si habia alguna
     * revision
     */

    function hay_Revision($tDia)
    {
        return (array_key_exists($tDia, $this->aFechas));
    }
}
