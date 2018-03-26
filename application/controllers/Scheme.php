<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
require APPPATH . '/libraries/BaseController.php';

class Scheme extends BaseController
{
    function __construct()  {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->database();
        $this->load->model('Scheme_model');
        $this->isLoggedIn();  
    } 

    /*
     * Listing of scheme
     */
    function index()
    {
        $data['scheme'] = $this->Scheme_model->get_all_scheme();
        $this->global['pageTitle']="List Schemes";
        
        $this->loadViews('scheme/index', $this->global, $data , NULL);
    }

    /*
     * Adding a new scheme
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('type_id','Type Id','required');
        $data_file= $this->do_upload();

        //echo $data_file;
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'type_id' => $this->input->post('type_id'),
				'scheme_name' => $this->input->post('scheme_name'),
				'maximum_amount' => $this->input->post('maximum_amount'),
				
				'creation_date' => date("Y-m-d h:i:sa"),
				'updation_date' => date("Y-m-d h:i:sa"),
				'fund_allocated' => $this->input->post('fund_allocated'),
				'created_by' => $this->global['name'],
				'updated_by' => $this->global['name'],
				'file_url' => $this->input->post('userfile'),
                'disease_id' => $this->input->post('disease_id'),
				'description' => $this->input->post('description'),
            );
            
            $disease = array(
            'disease type' => $this->input->post('disease type')
            );
            

            $scheme_id = $this->Scheme_model->add_scheme($params);
            redirect('scheme/index');

        }
        else
        {
			$this->load->model('Scheme_type_model');
            $this->load->model('Scheme_model');
			$data['all_scheme_type'] = $this->Scheme_type_model->get_all_scheme_type();
            $data['all_disease'] = $this->Scheme_model->get_all_disease();
            $this->global['pageTitle']="Add Schemes";
            $this->loadViews('scheme/add', $this->global, $data , NULL);
        }
    }  

    /*
     * Editing a scheme
     */

    public function do_upload() { 
         $config['upload_path']   = './uploads'; 
         $config['allowed_types'] = 'gif|jpg|png|pdf|csv|doc|docx'; 
         $config['max_size']      = 100; 
         $config['max_width']     = 1024; 
         $config['max_height']    = 768;  
         $this->load->library('upload', $config);
         
         if(!$this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
         }
         else
         {
            $data = array('upload_data' => $this->upload->data());
         }
         //$data= array('upload_data' => $this->upload->data());

         foreach ($error as $key => $value) {
             echo $value;
         }
         

      } 
    function edit($scheme_id)
    {   
        // check if the scheme exists before trying to edit it
        $data['scheme'] = $this->Scheme_model->get_scheme($scheme_id);
        
        if(isset($data['scheme']['scheme_id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('type_id','Type Id','required');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'type_id' => $this->input->post('type_id'),
                    'disease_id' => $this->input->post('disease_id'),
					'scheme_name' => $this->input->post('scheme_name'),
					'maximum_amount' => $this->input->post('maximum_amount'),
					'guidelines' => $this->input->post('guidelines'),
					'updation_date' => date("Y-m-d h:i:sa"),
					'fund_allocated' => $this->input->post('fund_allocated'),
					'updated_by' => $this->global['name'],
					'file_url' => $this->input->post('file_url'),
					'description' => $this->input->post('description'),
                );

                $this->Scheme_model->update_scheme($scheme_id,$params);            
                redirect('scheme/index');
            }
            else
            {
				$this->load->model('Scheme_type_model');
                $this->load->model('Scheme_model');
				$data['all_scheme_type'] = $this->Scheme_type_model->get_all_scheme_type();
                $data['all_disease'] = $this->Scheme_model->get_all_disease();
                $this->global['pageTitle']="Add Schemes";
                $this->loadViews('scheme/edit', $this->global, $data , NULL);
            }
        }
        else
            show_error('The scheme you are trying to edit does not exist.');
    } 

    /*
     * Deleting scheme
     */
    function remove($scheme_id)
    {
        $scheme = $this->Scheme_model->get_scheme($scheme_id);

        // check if the scheme exists before trying to delete it
        if(isset($scheme['scheme_id']))
        {
            $this->Scheme_model->delete_scheme($scheme_id);
            redirect('scheme/index');
        }
        else
            show_error('The scheme you are trying to delete does not exist.');
    }
    
}
