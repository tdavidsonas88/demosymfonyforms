<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

declare(strict_types=1);

use PrestaShop\Module\DemoSymfonyForms\Uploader\SupplierSecondImageUploader;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

if (!defined('_PS_VERSION_')) {
    exit;
}

class demosymfonyforms extends Module
{
    /**
     * @var ImageUploaderInterface
     */
    private $supplierSecondImageUploader;

    public function __construct()
    {
        $this->name = 'demosymfonyforms';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Demo Symfony Forms');
        $this->description = $this->l('Demonstration of how to insert an inputs inside the Symfony form');

        $this->supplierSecondImageUploader = new SupplierSecondImageUploader();
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionSupplierFormBuilderModifier')
            && $this->registerHook('actionAfterUpdateSupplierFormHandler');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionSupplierFormBuilderModifier(array $params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder->add('upload_file', FileType::class, [
            'label' => $this->getTranslator()->trans('Upload file', [], 'Modules.DemoSymfonyForms'),
            'required' => false,
        ]);
    }

    public function hookActionAfterUpdateSupplierFormHandler(array $data)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $data['form_data']['upload_file'];

        if ($uploadedFile instanceof UploadedFile) {
            $this->supplierSecondImageUploader->upload($data['id'], $uploadedFile);
        }
    }
}
