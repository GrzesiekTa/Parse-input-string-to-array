<?php

/**
 * Class parse data from string - example:
 * [ input value="asdasd" type="text" name="imie" placeholder="" class="" style="" validators="onlyNumbers, min(3), max(128)" items="1|aaa, 2|bbb" ]
 * Result getParseTags() = array:
[
	'value' => 'asdasd',
	'type' => 'text',
	'name' => 'imie',
	'placeholder' => '',
	'class' => '',
	'style' => '',
	'validators' => [
	'onlyNumbers' => '',
	'min' => '3',
	'max' => '128'
	],
	'items' =>[
		1 => 'aaa',
		2 => 'bbb'
	]
]
 */
class TagRecognizer {

    /**
     * @var string 
     */
    private $string;

    /**
     * 
     * @param string $string
     */
    public function __construct($string) {
        $this->string = $string;
    }

    /**
     * get array tags
     * 
     * @return array
     */
    public function getParseTags() {
        //get all between example="example" ::: (\w+) creates array $result[1]-----------([^"]*) creates array $result[2]
        preg_match_all('@(\w+)="([^"]*)"@', $this->string, $result);
        //new array created from $result[1](key) => $result[2](Value)
        $arrayTags = array_combine($result[1], $result[2]);

        return $this->parseTags($arrayTags);
    }

    /**
     * parse tags array to new array
     * 
     * @param array $arrayTags
     * 
     * @return array
     */
    private function parseTags(array $arrayTags) {

        $parseArrayTags = [];

        foreach ($arrayTags as $key => $value) {
            //items
            if ($key === 'items') {
                $parseArrayTags[$key] = $this->parseItems($value);
                //validators
            } elseif ($key === 'validators') {
                $parseArrayTags[$key] = $this->parseValidators($value);
                //simple tag
            } else {
                $parseArrayTags[$key] = $value;
            }
        }

        return $parseArrayTags;
    }

    /**
     *  parse items in string
     * 
     * @param string $value
     * 
     * @return array
     */
    private function parseItems($value) {
        $arrInfo = explode("#", $value);

        $newArray = [];
        foreach ($arrInfo as $item) {
            $values = explode("|", $item);
            $newArray[trim($values[0])] = $values[1];
        }

        return $newArray;
    }

    /**
     * parse validators in string
     * 
     * @param string $value
     * 
     * @return array
     */
    private function parseValidators($value) {
        $arrInfo = explode(",", $value);
        $newArray = [];
        foreach ($arrInfo as $item) {

            // if item has () in value - $keyValidatorValue is equal text between this brackets
            if (strpos($item, '(') !== false && strpos($item, ')') !== false) {

                preg_match('@\((.*?)\)@', $item, $match);

                $keyValidatorValue = $match[1];
            } else {
                $keyValidatorValue = '';
            }

            //$newArray[$item] - delete bracket with text and trim $item
            $newArray[trim(preg_replace("@\([^)]+\)@", "", $item))] = $keyValidatorValue;
        }

        return $newArray;
    }

}