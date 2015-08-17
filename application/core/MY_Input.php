<?php

class MY_Input extends CI_Input {

    /**
     * Fetch an item from the PUT array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    function put($index = NULL, $xss_clean = FALSE) {
        
        $data = file_get_contents('php://input');
        $PUT = json_decode($data, true);
        
        // Check if a field has been provided
        if ($index === NULL AND ! empty($PUT)) {
            $post = array();

            // Loop through the full _POST array and return it
            foreach (array_keys($PUT) as $key) {
                $post[$key] = $this->_fetch_from_array($PUT, $key, $xss_clean);
            }
            return $post;
        }

        return $this->_fetch_from_array($PUT, $index, $xss_clean);
    }

}
