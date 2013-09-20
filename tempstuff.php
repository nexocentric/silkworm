private function parseRows($array, $properties)
	{
		$rows = "";
		$rowAttributes = array();
		
		foreach($properties as $property) {
		    if(is_string($property)) {
				$rowAttributes[] = $property;
			}
		}
		//
		foreach($array as $row) {
			$properties = $this->parseCells($row);
			$rows .= $this->initializeTag(
				"tr",
				$rowAttributes
			);
		}
		
		return $rows;
	}

	public function autoTable($array)
	{
		//declarations
		$table = "";
		$tableAttributes = array();
		$rowAttributes = array();
		
		//initializations
		$properties = func_get_args();
		array_splice($properties, 0, 1); //array that the table is to parse
		
		//find the row attributes
		foreach($properties as $property) {
			if(is_array($property)) {
				$rowAttributes[] = $property;
			}
			if(is_string($property)) {
				$tableAttributes[] = $property;
			}
		}

		$tableAttributes[] = $this->parseRows($array, $rowAttributes);
		$table = $this->initializeTag(
			"table",
			$tableAttributes
		);
		
		return $table;
	}