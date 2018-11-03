# Parse-input-string-to-array

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