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
 * Extended Yii VarDumper
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class VarDumper extends \yii\helpers\VarDumper
{
    /**
     * @var string  internal output helper string
     */
    private static $_output = '';
    
    public static $format = [
        'indention' => 4,
        'linebreak' => true
    ];
    
    /**
     * @array   Formatting options used during the current export.
     */
    protected static $_currentFormat = [];
    
    /**
     * Exports a variable as a string representation.
     * 
     * {@inheritDoc}
     *
     * @param mixed $var the variable to be exported.
     * @return string a string representation of the variable
     */
    public static function export($var, array $format = [])
    {
        // prepare export format info
        static::$_currentFormat = array_merge(static::$format, $format);
        // Perform export
        static::$_output = '';
        static::exportInternalFormatted($var, 0);
        return static::$_output;
    }

    /**
     * Internal implementation of export
     * 
     * Note: This function is widely based on Yii's original implementation, but extended
     * by the formatting-options
     * 
     * @param mixed $var variable to be exported
     * @param integer $level depth level
     */
    private static function exportInternalFormatted($var, $level)
    {
        switch (gettype($var)) {
            case 'NULL':
                static::$_output .= 'null';
                break;
            case 'array':
                if (empty($var)) {
                    static::$_output .= '[]';
                } else {
                    $keys = array_keys($var);
                    $outputKeys = ($keys !== range(0, sizeof($var) - 1));
                    $spaces = str_repeat(' ', $level * static::$_currentFormat['indention']);
                    $i = 0;
                    $n = count($keys);
                    static::$_output .= '[';
                    foreach ($keys as $key) {
                        if (static::$_currentFormat['linebreak']) {
                            static::$_output .= "\n" . $spaces . str_repeat(' ', static::$_currentFormat['indention']);
                        } elseif ($i > 0) {
                            static::$_output .= ' ';
                        }
                        if ($outputKeys) {
                            static::exportInternalFormatted($key, 0);
                            static::$_output .= ' => ';
                        }
                        static::exportInternalFormatted($var[$key], $level + 1);
                        if ($i < $n-1) {
                            static::$_output .= ',';
                        }
                        $i++;
                    }
                    if (static::$_currentFormat['linebreak']) {
                        static::$_output .= "\n" . $spaces;
                    }
                    static::$_output .= ']';
                }
                break;
            case 'object':
                try {
                    $output = 'unserialize(' . var_export(serialize($var), true) . ')';
                } catch (\Exception $e) {
                    // serialize may fail, for example: if object contains a `\Closure` instance
                    // so we use regular `var_export()` as fallback
                    $output = var_export($var, true);
                }
                static::$_output .= $output;
                break;
            default:
                static::$_output .= var_export($var, true);
        }
    }
    
}
