function toggleEdit(section) {
        const isInfo = section === 'info';
        const fields = isInfo ? ['nombre_completo', 'telefono_contacto'] : ['password_actual', 'nueva_password',
            'confirmar_password'
        ];
        const buttonId = isInfo ? 'guardar-info' : 'guardar-pass';

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.disabled = false;
        });

        document.getElementById(buttonId).style.display = 'inline-block';
    }