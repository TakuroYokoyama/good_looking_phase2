<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use \Exception;
use \PDOException;

/**
 * Students Controller
 *
 * @property \App\Model\Table\StudentsTable $Students
 *
 * @method \App\Model\Entity\Student[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StudentsController extends AppController {
    public function initialize() {
        parent::initialize();
        $this->Staffs = TableRegistry::get('staffs');
        $this->Posts = TableRegistry::get('posts');
        $this->viewBuilder()->autoLayout(true);
        $this->viewBuilder()->layout('student');
        $this->loadComponent('Flash');
    }    

    public function index() {
        // Staffsテーブルから現在の社員数(del_flg=0)を取得
        $data = $this->Staffs->findByIsDeleted(0);

        $list = array();
        foreach ($data as $result) {
            array_push($list, $result['id']);
        }

        // 並び順で有利不利の無いようシャッフルする
        shuffle($list);
        $this->set('list', $list);
    }

    public function vote() {
        $person_no = $this->request->query('value');
        $imgpath = $person_no. '.jpg';
        $this->set('imgpath', $imgpath);
        $this->set('person_no', $person_no);
    }

    public function addVoteRecord() {
        $studentsRecord = $this->Students->newEntity();
        $postsRecord = $this->Posts->newEntity();

        $studentsRecord->name_initial = $this->request->data['f_name'].'・'.$this->request->data['l_name'];
        $studentsRecord->univ = h($this->request->data['univ']);
        $studentsRecord->ender = $this->request->data['gender'];

        try {
            $this->Students->save($studentsRecord);
        } catch (PDOException $e) {
            $this->Flash->error('登録できませんでした(table_s)'. $e->getMessage());
            return $this->redirect(['action' => 'index']);
        }

        $postsRecord->student_id = $studentsRecord->id;
        $postsRecord->staff_id = $this->request->data['person_no'];
        $postsRecord->roc_x = $this->request->data['roc_x'];
        $postsRecord->roc_y = $this->request->data['roc_y'];

        try {
            $this->Posts->save($postsRecord);
        } catch (PDOException $e) {
            $this->Flash->error('登録できませんでした(table_p)'. $e->getMessage());
            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'complete']);
    }

    public function complete() {

    }

}
