<?php
/**
 * Helper para obtener cabeceras de tabla pmr_rules y generar archivo csv 	
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @author Ing. Eduardo Ortiz
 * 
 */
namespace Adteam\Core\Admin\Rules;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Application\Entity\PmrRules;

class Layout
{
    /**
     *
     * @var ServiceManager 
     */
    protected $services;
    
    /**
     *
     * @var EntityManager 
     */
    protected $em;

    /**
     *
     * @var array
     */
    protected $config;

    /**
     * 
     * @param ServiceManager $services
     */
    public function __construct(ServiceManager $services) 
    {
        $this->em = $services->get(EntityManager::class);
        $this->config = $services->get('config');
    }

    /**
     * Genera archivo csv apartir de nombres de columna de tabla
     * pmr_rules que es customizable
     * 
     * @param type $filename
     * @return type
     */
    public function buildFile($filename)
    {
        $columns = $this->em->getClassMetadata(PmrRules::class)
                ->getFieldNames();
        $newColumns = $this->exeptionColumn($columns);
        return $this->buildCsv($newColumns, $filename);                
    }
    
    /**
     * Quita colmnas no necesarias
     * 
     * @param type $items
     * @return type
     */
    private function exeptionColumn($items)
    {
        $newItems = ['user_id'];
        foreach ($items as $item){
            if($item !== 'id'&& $item!=='createdAt'){
                $newItems[]=$item;
            }
        }
        return $newItems;
    }
    
    /**
     * Objeto csv
     * 
     * @param type $items
     * @param string $filename
     * @return string
     */
    private function buildCsv($items,$filename)
    {
        $filename = $this->config['path'].'/data/upload/csv/'.$filename;
        $fp = fopen($filename, "w");
        fputcsv($fp, $items, ',', '"');
        fclose($fp); 
        return $filename;
    }    
    
    
}
