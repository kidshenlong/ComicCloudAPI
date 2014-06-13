<?php
/**
 * Comics controller.
 *
 * @package api-framework
 * @author  Michael Patterson-Muir <michaelpm91@googlemail.com>
 */
class ComicsController extends AbstractController
{
    /**
     * Comics file.
     *
     * @var variable type
     */
    protected $comics_file = './data/comics.txt';

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
        $comics = $this->readComics();
        switch (count($request->url_elements)) {
            case 1:
                return $comics;
                break;
            case 2:
                $comic_id = $request->url_elements[1];
                return $comics[$comic_id];
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
                /*if(isset($request->parameters['comic_id']) && $request->parameters['comic_id']!=''){
                    $id = $request->parameters['comic_id'];
                }else{
                    $id = $this->generateRandomString();
                }*/
                $id = $this->generateRandomString();
                $comics = $this->readComics();
                $comics_array = array (
                    /*'user' => 'kidshenlong',
                    'series_title' => $request->parameters['series_title'],
                    'start_year' => $request->parameters['start_year'],
                    'cover_image' => $request->parameters['cover_image']*/
                    'user' => 'kidshenlong',
                    //'comic_id'=> $this->generateRandomString(),
                    'issue' => $request->parameters['issue'],
                    'cover_image' => $request->parameters['cover_image']
                );
                $comics[$request->parameters['series_id']][$id] = $comics_array;
                //$comics = '';
                $this->writeComics($comics);
                header('HTTP/1.1 201 Created');
                header('Location: /comics/'.$id);
                return null;
                break;
        }
    }

    /**
     * Read comics.
     *
     * @return array
     */
    protected function readComics(){
        $comics = unserialize(file_get_contents($this->comics_file));
        /*if (empty($series)) {
            $comics = array(
                '8lF1sN00IUcjWoNQcEoy' => array (
                    'user' => 'kidshenlong',
                    'series_title' => 'The Amazing Spider-Man',
                    'start_year' => '1963',
                    'cover_image' => 'http://upload.wikimedia.org/wikipedia/en/5/54/AmazingSpider-Man1.jpg'
                )
            );
            $this->writeComics($comics);
        }*/
        return $comics;
    }

    /**
     * Write Comics.
     *
     * @param  string $comics
     * @return boolean
     */
    protected function writeComics($comics){
        file_put_contents($this->comics_file, serialize($comics));
        return true;
    }
}