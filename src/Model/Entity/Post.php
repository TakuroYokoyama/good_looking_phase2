<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property int $id
 * @property int $student_id
 * @property int $staff_id
 * @property int $heart_x
 * @property int $heart_y
 * @property \Cake\I18n\FrozenTime $created_date
 * @property \Cake\I18n\FrozenTime $updated_date
 * @property bool $is_deleted
 *
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\Staff $staff
 */
class Post extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'student_id' => true,
        'staff_id' => true,
        'heart_x' => true,
        'heart_y' => true,
        'created_date' => true,
        'updated_date' => true,
        'is_deleted' => true,
        'student' => true,
        'staff' => true
    ];
}
