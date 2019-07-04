<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Song extends REST_Controller
{
    private $postData;
    private $header;

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('song_model');
        $this->postData = $this->request->body;
        $this->headers = $this->input->request_headers();
    }

    public function getallsongs_get() {
        $where = array(
            'is_deleted' => '0'
        );
        $songs = $this->song_model->getSongsByWhere($where);
        $this->set_response($songs, REST_Controller::HTTP_OK);
    }

    public function song_post() {
        if(!AUTHORIZATION::checkAdminAuth()) {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            $song_data['song_title'] = $this->postData['song_title'];
            $song_data['song_thumb'] = $this->postData['song_thumb'];
            $song_data['song_music'] = $this->postData['song_music'];
            $song_data['song_music_wav'] = $this->postData['song_music_wav'];
            $song_data['song_music_inst'] = $this->postData['song_music_inst'];
            $song_data['song_artist'] = $this->postData['song_artist'];
            $song_data['song_performedby'] = $this->postData['song_performedby'];
            $song_data['song_mood'] = $this->postData['song_mood'];
            $song_data['song_genre'] = $this->postData['song_genre'];
            $song_data['song_pace'] = $this->postData['song_pace'];
            $song_data['song_instrument'] = $this->postData['song_instrument'];
            $song_data['song_playlist'] = $this->postData['song_playlist'];
            $song_data['song_vocals_inst'] = $this->postData['song_vocals_inst'];
            $song_data['song_key'] = $this->postData['song_key'];
            $song_data['song_duration'] = $this->postData['song_duration'];
            $song_data['song_bpm'] = $this->postData['song_bpm'];
            $song_data['song_key_writers'] = json_encode($this->postData['song_key_writers']);
            $song_data['song_album_name'] =  $this->postData['song_album_name'];
            $song_data['song_release'] =  $this->postData['song_release'];
            $song_data['song_original_cover'] =  $this->postData['song_original_cover'];
            $song_data['song_explicit'] =  $this->postData['song_explicit'];
            $song_data['song_language'] =  $this->postData['song_language'];
            $song_data['song_comments_notes'] =  $this->postData['song_comments_notes'];
            $song_data['song_status'] =  $this->postData['song_status'];
            $song_data['song_music_lyrics'] =  $this->postData['song_music_lyrics'];
            
            $datestring = '%Y-%m-%d %h:%i:%s';
            $time = time();
            $song_data['created_datetime'] =  mdate($datestring, $time);

            $song_id = $this->song_model->addNewSong($song_data);
            if($song_id) {
                $this->set_response($song_data, REST_Controller::HTTP_OK);    
            } else {
                $this->set_response($song_data, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);    
            }
        }
    }


    public function song_patch($song_id) {
        if(!AUTHORIZATION::checkAdminAuth()) {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            $where = array(
                'song_id' => $song_id
            );

            $song_data['song_title'] = $this->postData['song_title'];
            $song_data['song_thumb'] = $this->postData['song_thumb'];
            $song_data['song_music'] = $this->postData['song_music'];
            $song_data['song_music_wav'] = $this->postData['song_music_wav'];
            $song_data['song_music_inst'] = $this->postData['song_music_inst'];
            $song_data['song_artist'] = $this->postData['song_artist'];
            $song_data['song_performedby'] = $this->postData['song_performedby'];
            $song_data['song_mood'] = $this->postData['song_mood'];
            $song_data['song_genre'] = $this->postData['song_genre'];
            $song_data['song_pace'] = $this->postData['song_pace'];
            $song_data['song_instrument'] = $this->postData['song_instrument'];
            $song_data['song_playlist'] = $this->postData['song_playlist'];
            $song_data['song_vocals_inst'] = $this->postData['song_vocals_inst'];
            $song_data['song_key'] = $this->postData['song_key'];
            $song_data['song_duration'] = $this->postData['song_duration'];
            $song_data['song_bpm'] = $this->postData['song_bpm'];
            $song_data['song_key_writers'] = json_encode($this->postData['song_key_writers']);
            $song_data['song_album_name'] =  $this->postData['song_album_name'];
            $song_data['song_release'] =  $this->postData['song_release'];
            $song_data['song_original_cover'] =  $this->postData['song_original_cover'];
            $song_data['song_explicit'] =  $this->postData['song_explicit'];
            $song_data['song_language'] =  $this->postData['song_language'];
            $song_data['song_comments_notes'] =  $this->postData['song_comments_notes'];
            $song_data['song_status'] =  $this->postData['song_status'];
            $song_data['song_music_lyrics'] =  $this->postData['song_music_lyrics'];

            $datestring = '%Y-%m-%d %h:%i:%s';
            $time = time();
            $song_data['updated_datetime'] =  mdate($datestring, $time);

            $this->song_model->updateSong($song_data, $where);

            $this->set_response($song_data, REST_Controller::HTTP_OK);
        }
    }

    public function song_get($song_id) {
        $song_item = $this->song_model->getSongById($song_id);
        $song_item['song_key_writers'] = json_decode($song_item['song_key_writers']);
        $this->set_response($song_item, REST_Controller::HTTP_OK);
    }

    public function song_delete($song_id) {
        if(!AUTHORIZATION::checkAdminAuth()) {
            $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
        } else {
            $where = array(
                'song_id' => $song_id
            );

            $song_data['is_deleted'] = '1';
            $datestring = '%Y-%m-%d %h:%i:%s';
            $time = time();
            $song_data['deleted_datetime'] =  mdate($datestring, $time);

            $this->song_model->updateSong($song_data, $where);

            $this->set_response($song_data, REST_Controller::HTTP_OK);
        }
    }

    public function songthumb_post() {
        $config['upload_path']          = './uploads/song/thumb/';
        $config['allowed_types']        = 'jpg|png';
        $config['max_size']             = 10000;
        $config['max_width']            = 10240;
        $config['max_height']           = 10240;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('song_thumb'))
        {
            $error = array('error' => $this->upload->display_errors());

            $this->set_response($error, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
        {
            $data = $this->upload->data();

            $return_data['url'] = base_url() . 'uploads/song/thumb/' . $data['file_name'];

            $this->set_response($return_data, REST_Controller::HTTP_OK);
        }
    }


    public function songmusic_post() {
        $config['upload_path']          = './uploads/song/music/';
        $config['allowed_types']        = 'mp3|wav';
        $config['max_size']             = 100000;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('song_music'))
        {
            $error = array('error' => $this->upload->display_errors());

            $this->set_response($error, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
        {
            $data = $this->upload->data();

            $return_data['url'] = base_url() . 'uploads/song/music/' . $data['file_name'];

            $this->set_response($return_data, REST_Controller::HTTP_OK);
        }
    }

    public function changefeatured_post($song_id) {
        $song = $this->song_model->getSongById($song_id);
        $this->set_response($song, REST_Controller::HTTP_OK);
        if($song) {
            $data = array(
                'is_featured' => 0
            );

            if($song['is_featured'] == 0) {
                $data['is_featured'] = 1;
            } else {
                $data['is_featured'] = 0;
            }

            $where = array(
                'song_id' => $song_id
            );

            $this->song_model->updateSong($data, $where);
            $this->set_response($data, REST_Controller::HTTP_OK);
        } else {
            $this->set_response(array('error'=> 'not exist'), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}