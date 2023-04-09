<?php
/**
 * Created by PhpStorm.
 * User: Aad Pouw
 * Date: 5-4-2023
 * Time: 05:18
 */
namespace TP_Admin\Classes;
use TP_Core\Traits\core_methods;
use TP_Core\Traits\img_methods;
use TP_Core\Traits\translate_methods;
use TP_Core\Traits\utils_methods;
if(ABSPATH) {
    class AdminSearch{
        use core_methods,img_methods,translate_methods,utils_methods;
        private $result = ['data' => [],'count' => 0,'status' => ''];
        public function result(){
            $locale = self::tp_get_locale('', LOCALE.LOCALESET.'admin/main.php'); //todo
            if ($this->authorizeAid()){
                $search_string = (string)filter_input(INPUT_GET, 'pagestring');
                if (self::tp_safe()) {
                    if (isset($search_string)) {
                        $available_pages = Admins::getInstance()->getAdminPages();
                        if (strlen($search_string) >= 2) {
                            $pages = array_merge([], $available_pages);
                            $result_rows = 0;
                            if (!empty($pages)) {
                                foreach ($pages as $page) {
                                    if (stristr($page['admin_title'], $search_string) === true || stristr($page['admin_link'], $search_string) === true) {
                                        $this->result['data'][] = $page;
                                        $result_rows++;
                                    }
                                }
                            } else {
                                $this->result['status'] = 102;
                            }
                            if ($result_rows > 0) {
                                $this->result['count'] = $result_rows;
                            } else {
                                $this->result['status'] = 104;
                            }
                        } else {
                            $this->result['status'] = 103;
                        }
                    }
                    $results = [];
                    $results['status'] = !empty($this->result['status']) ? $locale['search_msg_' . $this->result['status']] : '';
                    if (!empty($this->result)) {
                        $this->setResult($this->result);

                        if (!empty($this->result['data'])) {
                            $link = '';
                            foreach ($this->result['data'] as $data) {
                                if (stripos($data['admin_link'], '/todo/') !== false) {//todo
                                    $link .= self::tp_get_settings('siteurl') . 'todo/' . $data['admin_link'];//todo
                                } else {
                                    $link .= self::tp_get_settings('siteurl') . 'administration/' . $data['admin_link'];
                                }
                                $link .= self::tp_get_aid_link();
                                $title = $data['admin_title'];
                                if ($data['admin_page'] !== 5) {
                                    $title = $locale[$data['admin_rights']] ?? $title;
                                }
                                $icon = strtr(self::get_image('ac_' . $data['admin_rights']), [
                                    TAILORS => self::tp_get_settings('siteurl') . 'infusions/',
                                    ADMIN => self::tp_get_settings('siteurl') . 'administration/'
                                ]);
                                if (self::check_rights($data['admin_rights'])) {
                                    $result = ['title' => $title,'link' => $link,'icon' => $icon];
                                    $results[] = $result;
                                }
                            }
                        }
                    }
                    return json_encode($results);
                }
                $this->result['status'] = 101;
            }else{
                $this->result['status'] = 100;
            }
            return null;
        }//40
        private function authorizeAid(){
            $aid = (string)filter_input(INPUT_GET, 'aid');
            return defined('iAUTH') && isset($aid) && iAUTH === $aid;
        }//131
        private function setResult($result) {
            $this->result = $result;
        }//141
    }
}else {die;}