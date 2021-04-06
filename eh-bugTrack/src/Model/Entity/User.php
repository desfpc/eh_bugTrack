<?php
declare(strict_types = 1);

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $pass
 */
class User extends Entity
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
        'name' => true,
        'login' => true,
        'pass' => true,
    ];

    /**
     * @param string $value
     * @return false|string
     */
    protected function _setPass(string $value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
}
