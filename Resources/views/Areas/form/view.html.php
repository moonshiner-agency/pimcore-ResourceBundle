<?php
$this->slots()->components[] = ['csrfToken' => $csrfToken, 'type' => 'form', 'id' => $form_id, 'action' => $form->vars['action'], 'method' => $form->vars['method'], 'data' => $formData];
