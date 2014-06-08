<?php
/**
 * Series controller.
 * 
 * @package api-framework
 * @author  Michael Patterson-Muir <michaelpm91@googlemail.com>
 */
class SeriesController extends AbstractController
{
    /**
     * Series file.
     *
     * @var variable type
     */
    protected $series_file = './data/series.txt';
    
    /**
     * Generate Series IDs.
     *
     * @param  int $length
     * @return string
     */
    protected function generateRandomString($length = 20) {
    
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
    	return $randomString;
    	
	}
    
    /**
     * GET method.
     * 
     * @param  Request $request
     * @return string
     */
    public function get($request){
        $series = $this->readSeries();
        switch (count($request->url_elements)) {
            case 1:
                return $series;
            break;
            case 2:
                $series_id = $request->url_elements[1];
                return $series[$series_id];
            break;
        }
    }
    
    /**
     * POST action.
     *
     * @param  $request
     * @return null
     */
    public function post($request){
        switch (count($request->url_elements)) {
            case 1:
                // validation should go here
                //$id = (count($comics) + 1);                    
				if(isset($request->parameters['series_id']) && $request->parameters['series_id']!=''){
					$id = $request->parameters['series_id'];
				}else{
					$id = $this->generateRandomString();
				}
                $series = $this->readSeries();
                $series_array= array (
                	'user' => 'kidshenlong',
                    'series_title' => $request->parameters['series_title'],
                    'start_year' => $request->parameters['start_year'],
                    'cover_image' => $request->parameters['cover_image']
                );
                $series[$id] = $series_array;
                $this->writeSeries($series);
                header('HTTP/1.1 201 Created');
                header('Location: /series/'.$id);
                return null;
            break;
        }
    }
    
    /**
     * Read comics.
     *
     * @return array
     */
    protected function readSeries(){
        $series = unserialize(file_get_contents($this->series_file));
        if (empty($series)) {
            $series = array(
                '8lF1sN00IUcjWoNQcEoy' => array (
                	'user' => 'kidshenlong',
                    'series_title' => 'The Amazing Spider-Man',
                    'start_year' => '1963',
                    'cover_image' => 'http://upload.wikimedia.org/wikipedia/en/5/54/AmazingSpider-Man1.jpg'
                )
            );
            $this->writeSeries($series);
        }
        return $series;
    }
    
    /**
     * Write Comics.
     *
     * @param  string $comics
     * @return boolean
     */
    protected function writeSeries($series){
        file_put_contents($this->series_file, serialize($series));
        return true;
    }
}