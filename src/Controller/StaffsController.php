<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Staffs Controller
 *
 * @property \App\Model\Table\StaffsTable $Staffs
 *
 * @method \App\Model\Entity\Staff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffsController extends AppController
{
	public function initialize(){
        //cakephpデフォルトデザイン初期化
        $this->name = "Staffs";
        $this->autoRender = true;
        $this->viewBuilder()->autoLayout(false);
        header('Content-Type: application/json');

        $this->Posts = TableRegistry::get('posts');
        $this->Students = TableRegistry::get('students');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        //login画面にリダイレクトする(method名とctp名をあわせるため)
        return $this->redirect(['action' => "login"]);
    }

    /**
    *    ログイン画面
    */
    public function login() {
        $id = $this->request->data('id');
        $pass = $this->request->data('pass');
        $connection = ConnectionManager::get('default');

        //ログインできるユーザ・パスワードを固定
        $idAdmin = "administrator";
        $passAdmin = "admin";

        //ログインチェック
        if($this->request->is('post')){
            if( strcmp($id,$idAdmin) == 0 && strcmp($pass,$passAdmin) == 0){
                return $this->redirect(['action' => "aggregate"]);
             }else{
                $this->set("loginErrMessage","ログイン失敗");
             }
         }
    }

    /**
    *    グラフ画面
    */
    public function aggregate() {
        $this->viewBuilder()->className('Staffs');

        $connection = ConnectionManager::get('default');
        $results = $connection->query('SELECT p.staff_id, COUNT(*) as vote, s.name
                                            FROM posts p
                                            INNER JOIN staffs s
                                            ON p.staff_id = s.id
                                            GROUP BY staff_id
                                            ORDER BY vote DESC;')->fetchAll('assoc');

        $labels = array();
        $graphDatas = array();
        foreach ($results as $result) {
            array_push($labels, "\"".$result['name']."\"");
            array_push($graphDatas, $result['vote']);
        }

        $labels = implode(",", $labels);
        $graphDatas = implode(",", $graphDatas);

        $this->set("labels", $labels);
        $this->set("graphData", $graphDatas);
    }

    /**
    *    グラフソート
    *    Ajaxで呼ばれる非同期メソッド
    */
    public function sortGraph(){
        $this->viewBuilder()->className('Staffs');

        $sortType = $_POST['filter'];

        if($sortType === 'desc'){
            $connection = ConnectionManager::get('default');
            $results = $connection->query('SELECT p.staff_id, COUNT(*) as vote, s.name
                                            FROM posts p
                                            INNER JOIN staffs s
                                            ON p.staff_id = s.id
                                            GROUP BY staff_id
                                            ORDER BY vote DESC;')->fetchAll('assoc');
        } elseif($sortType === 'asc'){
            $connection = ConnectionManager::get('default');
            $results = $connection->query('SELECT p.staff_id, COUNT(*) as vote, s.name
                                            FROM posts p
                                            INNER JOIN staffs s
                                            ON p.staff_id = s.id
                                            GROUP BY staff_id
                                            ORDER BY vote ASC;')->fetchAll('assoc');
        } elseif($sortType === 'man'){
            $connection = ConnectionManager::get('default');
            $results = $connection->query('SELECT p.staff_id, COUNT(*) as vote, s.name, st.gender FROM posts p
											INNER JOIN staffs s 
											ON p.staff_id = s.id
											INNER JOIN students st
											ON p.student_id = st.id
											where st.gender = 1
											GROUP BY staff_id;
                                            ORDER BY vote DESC;')->fetchAll('assoc');
        } elseif($sortType === 'woman'){
            $connection = ConnectionManager::get('default');
            $results = $connection->query('SELECT p.staff_id, COUNT(*) as vote, s.name, st.gender FROM posts p
											INNER JOIN staffs s 
											ON p.staff_id = s.id
											INNER JOIN students st
											ON p.student_id = st.id
											where st.gender = 2
											GROUP BY staff_id;
                                            ORDER BY vote DESC;')->fetchAll('assoc');
        }

        $labels = array();
        $graphDatas = array();
        foreach ($results as $result) {
            array_push($labels, "\"".$result['name']."\"");
            array_push($graphDatas, $result['vote']);
        }

        $labels = implode(",", $labels);
        $graphDatas = implode(",", $graphDatas);

        $this->set("labels", $labels);
        $this->set("graphData", $graphDatas);
        echo json_encode($results);

        $this->autoRender = false;
    }

    /**
    *    新規登録
    */
    public function registration()
    {
        
    }

    public function addStaff(){
    	$this->autoRender = false;
    	$staffData = $this->Staffs->newEntity($this->request->data);
    	$this->log($this->request->data, LOG_DEBUG);
    	return $this->redirect(['action' => "login"]);
    }

    /**
    *    社員編集登録
    */
    public function edit(){
        $id = '1';
        $staffData = $this->Staffs->find()
        							->select(['Staffs.id', 'Staffs.name', 'Staffs.img_path', 'p.staff_id','vote' => 'count(*)'])
									->where(['Staffs.id' => $id])
									->join([
										'table' 		=> 'posts',
										'alias' 		=> 'p',
										'type'			=> 'INNER',
										'conditions'	=> 'p.staff_id = Staffs.id'
									])
									->group('Staffs.id')
									->first();

        $this->set('staffData', $staffData);
    }

    /**
    *    社員編集DB登録
    */
    public function updateStaff(){
        if($this->request->is('post')) {
            try {
                $post = $this->Clients->newEntity($this->request->data);
                $this->Clients->save($post);

                $fileName = $post['UploadData']['tmp_name'];
            	$imgName = $post['person_no'].".jpg";
            	move_uploaded_file($fileName,'img/'.$imgName);

                return $this->redirect(['action' => 'aggregate']);
            } catch(\PDOException $e) {
                $this->set('ErrMessage',"編集できませんでした");
                $this->set('id', $post['person_no']);
                $this->set('name', $post['name_initial']);
                $this->set('message', "社員情報を修正してください");
                $this->set('title', "社員情報編集");
                $this->set('pass', "<img src=/img/".$post['person_no'].".jpg>");
                $this->set('entity', $this->Clients->newEntity());
                $this->render('regist');
            }
        }
    }

	/**
    *    社員詳細
    */
    public function detail(){
        // if($this->request->is('post')) {
        //     //社員番号取得
        //     $id = $this->request->data('person_no');
        // } else {
        //     //不正アクセスの場合、ログイン画面に戻す
        //     return $this->redirect(['action' => 'login']);
        // }
        $id = '1';
        $staffData = $this->Staffs->find()
        							->select(['Staffs.id', 'Staffs.name', 'Staffs.img_path', 'p.staff_id','vote' => 'count(*)'])
									->where(['Staffs.id' => $id])
									->join([
										'table' 		=> 'posts',
										'alias' 		=> 'p',
										'type'			=> 'INNER',
										'conditions'	=> 'p.staff_id = Staffs.id'
									])
									->group('Staffs.id')
									->first();

        $this->set('staffData', $staffData);
    }

    /**
    *    社員削除
    */
    public function deleteStaff() {
        // if($this->request->is('post')) {
        //     $post = $this->request->getData();
        //     $recode = $this->Staffs->get($post['person_no']);
        //     $recode = $this->Staffs->patchEntity($recode, $post);
        //     $this->Staffs->save($recode);

        //     return $this->redirect(['action' => 'aggregate']);
        // }
        $this->autoRender = false;
        $this->log($this->request->data(), LOG_DEBUG);
    }
}
