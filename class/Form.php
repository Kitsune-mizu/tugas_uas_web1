<?php
/**
 * Class Form
 * Deskripsi: Class untuk membuat form inputan
 */
class Form {
    private $fields = array();
    private $action;
    private $method = "POST";
    private $submit = "Submit Form";
    private $enctype = "";
    private $resetButton = true;

    public function __construct($action = "", $submit = "Submit", $method = "POST", $enctype = "") {
        $this->action = $action;
        $this->submit = $submit;
        $this->method = $method;
        $this->enctype = $enctype;
    }

    public function addField($name, $label, $type = "text", $value = "", $options = array()) {
        $this->fields[] = array(
            'name' => $name,
            'label' => $label,
            'type' => $type,
            'value' => $value,
            'options' => $options
        );
    }

    public function setResetButton($show) {
        $this->resetButton = $show;
    }

    public function render() {
        $enctypeAttr = $this->enctype ? " enctype='{$this->enctype}'" : "";
        echo "<form action='{$this->action}' method='{$this->method}'{$enctypeAttr} class='form'>";
        echo '<div class="form-grid">';
        
        foreach ($this->fields as $field) {
            $this->renderField($field);
        }
        
        echo '</div>';
        echo '<div class="form-actions">';
        
        if ($this->resetButton) {
            echo "<button type='reset' class='btn btn-secondary'><i class='fas fa-redo'></i> Reset</button>";
        }
        
        echo "<button type='submit' class='btn btn-primary'><i class='fas fa-save'></i> {$this->submit}</button>";
        echo '</div>';
        echo '</form>';
    }

    private function renderField($field) {
        // Ambil nilai field (digunakan untuk text, number, select)
        $fieldValue = htmlspecialchars($field['value'] ?? '');
        
        // Default: required. Bisa dihilangkan dengan menyetel ['options']['required'] = false.
        $isRequired = (isset($field['options']['required']) && $field['options']['required'] === false) ? false : true;
        $requiredAttr = $isRequired ? 'required' : '';

        echo '<div class="form-group">';
        echo "<label for='{$field['name']}' class='form-label'>";
        
        // Add icon based on field type
        $icon = $this->getIcon($field['name']);
        if ($icon) {
            echo "<i class='fas fa-{$icon}'></i>";
        }
        
        echo "{$field['label']}</label>";
        
        switch ($field['type']) {
            case 'select':
                echo "<select id='{$field['name']}' name='{$field['name']}' class='form-control' {$requiredAttr}>";
                echo "<option value=''>Pilih {$field['label']}</option>";
                foreach ($field['options'] as $key => $value) {
                    $selected = ($key == $field['value']) ? "selected" : "";
                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                }
                echo "</select>";
                break;
                
            case 'file':
                echo "<input type='file' id='{$field['name']}' name='{$field['name']}' class='form-control' accept='image/*' {$requiredAttr}>";
                if (isset($field['options']['current'])) {
                    if ($field['options']['current']) {
                        // Tampilkan gambar preview jika ada
                        echo "<div class='current-image-preview'>";
                        echo "<small class='form-text'>Gambar saat ini:</small><br>";
                        echo "<img src='{$field['options']['current']}' style='max-width: 150px; margin-top: 10px; border: 1px solid #ddd; padding: 5px;'>";
                        echo "</div>";
                    } else {
                        echo "<small class='form-text'>Belum ada gambar</small>";
                    }
                }
                break;
                
            case 'number':
                $min = isset($field['options']['min']) ? "min='{$field['options']['min']}'" : "";
                $step = isset($field['options']['step']) ? "step='{$field['options']['step']}'" : "";
                echo "<input type='number' id='{$field['name']}' name='{$field['name']}' class='form-control' value='{$fieldValue}' {$requiredAttr} {$min} {$step}>";
                break;
                
            case 'password':
                echo "<input type='password' id='{$field['name']}' name='{$field['name']}' class='form-control' value='' {$requiredAttr}>";
                break;
                
            default:
                echo "<input type='{$field['type']}' id='{$field['name']}' name='{$field['name']}' class='form-control' value='{$fieldValue}' {$requiredAttr}>";
                break;
        }
        
        echo '</div>';
    }
    
    private function getIcon($fieldName) {
        $icons = [
            'nama' => 'tag',
            'kategori' => 'layer-group',
            'harga_beli' => 'shopping-cart',
            'harga_jual' => 'tags',
            'stok' => 'boxes',
            'file_gambar' => 'image',
            'username' => 'user',
            'password' => 'lock'
        ];
        
        return isset($icons[$fieldName]) ? $icons[$fieldName] : 'edit';
    }
}
?>