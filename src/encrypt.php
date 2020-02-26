<?php
/*
 * Project zfLogin - encrypt.php
 * Copyright (c) 2020 Sylpha Project Co., Ltd.
 * Create: 20200226 13:30:33
 * Finish: 20200226 18:05:20
 */

/*
 * Copyright (c) 2003-2005  Tom Wu
 * All Rights Reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS-IS" AND WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS, IMPLIED OR OTHERWISE, INCLUDING WITHOUT LIMITATION, ANY 
 * WARRANTY OF MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * IN NO EVENT SHALL TOM WU BE LIABLE FOR ANY SPECIAL, INCIDENTAL,
 * INDIRECT OR CONSEQUENTIAL DAMAGES OF ANY KIND, OR ANY DAMAGES WHATSOEVER
 * RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER OR NOT ADVISED OF
 * THE POSSIBILITY OF DAMAGE, AND ON ANY THEORY OF LIABILITY, ARISING OUT
 * OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * In addition, the following condition applies:
 *
 * All redistributions must retain an intact copy of this copyright notice
 * and disclaimer.
 */

$BI_RM = "0123456789abcdefghijklmnopqrstuvwxyz";

function int2char($n)
{   
    global $BI_RM;
    return $BI_RM[$n];
}

/*
 * base64.js
 */

$b64map="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
$b64pad="=";

function hex2b64($h) 
{
    global $b64map;
    global $b64pad;
    //$i;
    //$c;
    $ret = "";
    
    for($i = 0; $i+3 <= strlen($h); $i+=3)     //h.length -> strlen($h)
    {
        $c = intval(substr($h,$i,3),16);   //parseInt -> intval,substring -> substr 
        $ret = $ret . $b64map[$c >> 6] . $b64map[$c &63];  //js:+ 字符串拼接
    }
  
    if($i+1 == strlen($h)) 
    {
        $c = intval(substr($h,$i,1),16);
        $ret = $ret . $b64map[$c << 2];
    }
    elseif($i+2 == strlen($h))
    {
        $c = intval(substr($h,$i,2),16);
        $ret = $ret.$b64map[$c >> 2] . $b64map[($c & 3) << 4];
    }
    while((strlen($ret) & 3) > 0)
    {
        $ret = $ret. $b64pad;
    }
    return $ret;
} 

function b64tohex($s) 
{   
    global $b64map;
    global $b64pad;
    $ret = "";
    //var i;
    $k = 0; // b64 state, 0-3
    //$slop;
    //$v;
    for($i = 0; $i < strlen($s); ++$i) 
    {
        if($s[$i] == $b64pad) break;

        $v = strpos($b64map,$s[$i]);
        if($v < 0) continue;
        if($k == 0) 
        {
            $ret = $ret. int2char($v >> 2);
            $slop = $v & 3;
            $k = 1;
        }
        elseif($k == 1) 
        {
            $ret = $ret. int2char(($slop << 2) | ($v >> 4));
            $slop = $v & 0xf;
            $k = 2;
        }
        elseif($k == 2) 
        {
            $ret = $ret . int2char($slop);
            $ret = $ret . int2char($v >> 2);
            $slop = $v & 3;
            $k = 3;
        }
        else 
        {
            $ret = $ret . int2char(($slop << 2) | ($v >> 4));
            $ret = $ret . int2char($v & 0xf);
            $k = 0;
        }
    }
    if($k == 1)
    {
        $ret =  $ret . int2char($slop << 2);
    }
    return $ret;
}

// convert a base64 string to a byte/number array
/*
function b64toBA(s) {
    //piggyback on b64tohex for now, optimize later
    var h = b64tohex(s);
    var i;
    var a = new Array();
    for(i = 0; 2*i < h.length; ++i) {
      a[i] = parseInt(h.substring(2*i,2*i+2),16);
    }
    return a;
  }
*/

?>