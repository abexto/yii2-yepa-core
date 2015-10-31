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
 * Static utility class to maniuplate Yii rules
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class Rules
{
    /**
     * Removes specific rules from a rule definition array
     * 
     * The filter is specified as associative array of attributes => validators in the $excludeRules
     * parameter. The value can be '*' in order to remove all rules for the attribute or a single or 
     * an array of validator names. 
     * 
     * @param array $rules          Current rule definition array
     * @param array $excludeRules   Filter
     */
    public static function filter(array $rules = [], array $excludeRules = [])
    {
        if (empty($excludeRules)) {
            return $rules; // Nothing to remove ==> RETURN original
        }
        
        //
        // Filter the rules
        $result = $rules;
        foreach ($result as $rk => &$rv) {
            if (is_array($rv[0])) {
                foreach ($rv[0] as $fk => $fv) {
                    if (array_key_exists($fv, $excludeRules)) {
                        if (in_array('*', (array) $excludeRules[$rk]) ||
                                in_array($rv[1], (array) $excludeRules[$rk])) {
                            unset($rv[$fk]);
                        }
                    }
                }
                if (empty($rv[0])) {
                    unset($result[$rk]);
                }
            } else {
                if (array_key_exists($rv[0], $excludeRules)) {
                    if (in_array('*', (array) $excludeRules[$rk]) ||
                            in_array($rv[1], (array) $excludeRules[$rk])) {
                        unset($result[$rk]);
                    }
                }
            }
        }
        return $result;
    }
            
}
