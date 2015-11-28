<?php

/*
 * Copyright (c) 2015, Andreas Prucha, Abexto (Helicon Software Development)
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * *  Redistributions of source code must retain the above copyright notice, this 
 *    list of conditions and the following disclaimer.
 * *  Redistributions in binary form must reproduce the above copyright notice, 
 *    this list of conditions and the following disclaimer in the documentation 
 *    and/or other materials provided with the distribution.
 * *  Neither the name of Abexto, Helicon Software Development, Andreas Prucha
 *    nor the names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace abexto\yepa\core\helpers;

/**
 * Collection of misc. utility functions
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class Utils
{
    /**
     * Normalizes namespace of a PHP idientifier
     * 
     * @param type $value               Identifier to normalize
     * @param type $leadingNsSeparator  Ensure that identifer starts (if set to true), 
     *                                  or does not start with a (if false) with a PHP namespace separator
     * @param type $trailingNsSeparator Ensure that identifer ends (if set to true), 
     *                                  or does not start with a (if false) with a PHP namespace separator
     * @return string
     */
    public function normalizeIdentifierNs($value, $leadingNsSeparator = true, $trailingNsSeparator = false)
    {
        $result = trim($value);
        if (strlen($value) == 0 && ($leadingNsSeparator || $trailingNsSeparator)) {
            $result = '\\';
        } else {
            $result = ($leadingNsSeparator ? '\\' : '').ltrim($value, '\\');
            $result .= ($trailingNsSeparator ? '\\' : '');
        }
        return $result;
    }
    
    /**
     * Concatenates a PHP namespace with an identifier
     * 
     * @param string $aNamespace
     * @param string $aIdentifier
     * @return string
     */
    public function combineIdentifier($aNamespace, $aIdentifier)
    {
        return rtrim($aNamespace, '\\').'\\'.ltrim($aIdentifier, '\\');
    }
    
    
}
