<?php
/**
* Plugin Name: Плагин безопасности
* Version: 1.0
* Author: Иван Гриненко
**/

/* Меню настроек */

function dbi_render_plugin_settings_page() {
    ?>
    <h2>Настройки безопасности</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'dbi_security_plugin_options' );
        do_settings_sections( 'dbi_security_plugin' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Сохранить' ); ?>" />
    </form>
    <?php
}

function dbi_add_settings_page() {
    add_options_page( 'Страница настроек безопасности', 'Настройки безопасности', 'manage_options', 'dbi-security-plugin', 'dbi_render_plugin_settings_page' );
}

add_action( 'admin_menu', 'dbi_add_settings_page' );

function dbi_register_settings() {
    register_setting( 'dbi_security_plugin_options', 'dbi_security_plugin_options', 'dbi_security_plugin_options_validate' );

    /* Уязвимости */

    add_settings_section( 'security_settings', 'Уязвимости', 'dbi_security_settings_text', 'dbi_security_plugin' );

    add_settings_field( 'dbi_plugin_setting_change_admin', 'Изменить логин admin', 'dbi_plugin_setting_change_admin', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_change_login', 'Изменить страницу входа (Ярлык)', 'dbi_plugin_setting_change_login', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_block_login', 'Ограничить количество попыток входа', 'dbi_plugin_setting_block_login', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_max_login_attempts', 'Максимальное количество попыток входа', 'dbi_plugin_setting_max_login_attempts', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_block_duration', 'Период блокировки (минут)', 'dbi_plugin_setting_block_duration', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_use_recaptcha', 'Использовать ReCaptcha', 'dbi_plugin_setting_use_recaptcha', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_recaptcha_site_key', 'ReCaptcha Site Key', 'dbi_plugin_setting_recaptcha_site_key', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_recaptcha_secret_key', 'ReCaptcha Secret Key', 'dbi_plugin_setting_recaptcha_secret_key', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_block_unauthorized', 'Блокировать ботов и неавторизованных пользователей', 'dbi_plugin_setting_block_unauthorized', 'dbi_security_plugin', 'security_settings' );
    add_settings_field( 'dbi_plugin_setting_set_to_maintenance', 'Перевести сайт в режим тех. обслуживания', 'dbi_plugin_setting_set_to_maintenance', 'dbi_security_plugin', 'security_settings' );

    /* Оптимизация */

    add_settings_section( 'reports', 'Оптимизация', 'dbi_reports_text', 'dbi_security_plugin' );

    add_settings_field( 'dbi_plugin_setting_generate_xml_map', 'Сгенерировать XML-карту', 'dbi_plugin_setting_generate_xml_map', 'dbi_security_plugin', 'reports' );
    add_settings_field( 'dbi_plugin_setting_generate_robots', 'Сгенерировать robots.txt', 'dbi_plugin_setting_generate_robots', 'dbi_security_plugin', 'reports' );
    add_settings_field( 'dbi_plugin_setting_set_seo_metatags', 'Проставлять SEO-метатеги', 'dbi_plugin_setting_set_seo_metatags', 'dbi_security_plugin', 'reports' );
    add_settings_field( 'dbi_plugin_setting_set_seo_keywords', 'Ключевые слова для SEO', 'dbi_plugin_setting_set_seo_keywords', 'dbi_security_plugin', 'reports' );

}


add_action( 'admin_init', 'dbi_register_settings' );

function dbi_security_plugin_options_validate( $input ) {
    if ( $newinput['max_login_attempts'] < 3 ) {
        $newinput['max_login_attempts'] = 3;
    }

    if ( $newinput['block_duration'] < 1 ) {
        $newinput['block_duration'] = 1;
    }

    return $input;
}

function dbi_security_settings_text() {
    echo '<p>Здесь вы можете настроить уязвимости сайта</p>';
}
function dbi_reports_text() {
    echo '<p>Здесь вы можете настроить оптимизацию сайта</p>';
    echo '<p>Сгенерированные файлы можно найти в папке wp-content/uploads</p>';
}

function dbi_plugin_setting_change_admin() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_change_admin' name='dbi_security_plugin_options[change_admin]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["change_admin"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["change_admin"], 1 );
    }
}

function dbi_plugin_setting_change_login() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_change_login' name='dbi_security_plugin_options[change_login]' type='text' value='";
    if (empty($options) or empty($options["change_login"])) {
        echo "' />";
    }
    else {
        echo $options["change_login"] . "' />";
    }
}

function dbi_plugin_setting_block_login() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_block_login' name='dbi_security_plugin_options[block_login]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["block_login"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["block_login"], 1 );
    }
}

function dbi_plugin_setting_max_login_attempts() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_max_login_attempts' name='dbi_security_plugin_options[max_login_attempts]' min='3' type='number' value='";
    if (empty($options) or empty($options["max_login_attempts"])) {
        echo "' />";
    }
    else {
        echo $options["max_login_attempts"] . "' />";
    }
}

function dbi_plugin_setting_block_duration() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_block_duration' name='dbi_security_plugin_options[block_duration]' min='1' type='number' value='";
    if (empty($options) or empty($options["block_duration"])) {
        echo "' />";
    }
    else {
        echo $options["block_duration"] . "' />";
    }
}

function dbi_plugin_setting_use_recaptcha() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_use_recaptcha' name='dbi_security_plugin_options[use_recaptcha]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["use_recaptcha"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["use_recaptcha"], 1 );
    }
}

function dbi_plugin_setting_recaptcha_site_key() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_recaptcha_site_key' name='dbi_security_plugin_options[recaptcha_site_key]' type='text' value='";
    if (empty($options) or empty($options["recaptcha_site_key"])) {
        echo "' />";
    }
    else {
        echo $options["recaptcha_site_key"] . "' />";
    }
}

function dbi_plugin_setting_recaptcha_secret_key() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_recaptcha_secret_key' name='dbi_security_plugin_options[recaptcha_secret_key]' type='text' value='";
    if (empty($options) or empty($options["recaptcha_secret_key"])) {
        echo "' />";
    }
    else {
        echo $options["recaptcha_secret_key"] . "' />";
    }
}

function dbi_plugin_setting_block_unauthorized() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_block_unauthorized' name='dbi_security_plugin_options[block_unauthorized]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["block_unauthorized"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["block_unauthorized"], 1 );
    }
}

function dbi_plugin_setting_set_to_maintenance() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_set_to_maintenance' name='dbi_security_plugin_options[set_to_maintenance]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["set_to_maintenance"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["set_to_maintenance"], 1 );
    }
}

// Оптимизация

function dbi_plugin_setting_generate_xml_map() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_generate_xml_map' name='dbi_security_plugin_options[generate_xml_map]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["generate_xml_map"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["generate_xml_map"], 1 );
    }
}

function dbi_plugin_setting_generate_robots() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_generate_robots' name='dbi_security_plugin_options[generate_robots]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["generate_robots"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["generate_robots"], 1 );
    }
}

function dbi_plugin_setting_set_seo_metatags() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_set_seo_metatags' name='dbi_security_plugin_options[set_seo_metatags]' type='checkbox' value=\"1\" ";
    if (empty($options) or empty($options["set_seo_metatags"])) {
        checked( 0, 1 );
    }
    else {
        checked( $options["set_seo_metatags"], 1 );
    }
}

function dbi_plugin_setting_set_seo_keywords() {
    $options = get_option( 'dbi_security_plugin_options' );

    echo "<input id='dbi_plugin_setting_seo_keywords' name='dbi_security_plugin_options[seo_keywords]' type='text' value='";
    if (empty($options) or empty($options["seo_keywords"])) {
        echo "' />";
    }
    else {
        echo $options["seo_keywords"] . "' />";
    }
}

/* Меню настроек */

/* Реализация функций безопасности */

function sec_username_changer() {
    $admin_user = get_user_by('login', 'admin');
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty($options["change_admin"]) and $options["change_admin"] == 1) {
        $changeAdmin = $options["change_admin"];
        $options["change_admin"] = 0;
        update_option('dbi_security_plugin_options', $options);

        if ($changeAdmin and $admin_user) {
            $new_username = 'user_' . wp_generate_password(4, false);

            if (! username_exists($new_username)) {
                global $wpdb;
                $wpdb->update(
                    $wpdb->users,
                    ['user_login' => $new_username],
                    ['ID' => $admin_user->ID]
                );
            }

            wp_mail(
                get_option('admin_email'),
                'Имя пользователя admin изменено',
                'Имя пользователя admin было изменено на ' . $new_username
            );
        }

    }
}

add_action( 'admin_init', 'sec_username_changer' );

function sec_change_login() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["change_login"]) ) {
        $new_login_slug = $options["change_login"];

        add_action('init', function() use ($new_login_slug) {
            global $wp_rewrite;
            add_rewrite_rule('^', $new_login_slug, '/?$', 'wp-login.php', 'top');

            add_action('wp_loaded', function() use ($new_login_slug) {
                if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
                    wp_redirect(home_url($new_login_slug));
                    exit;
                }
            });

            flush_rewrite_rules();
        });
    }
}

add_action( 'init', 'sec_change_login' );

/* Блокировка Входа */

function sec_block_ip($ip) {
    $options = get_option( 'dbi_security_plugin_options' );

    if (empty($options) or empty($options["block_duration"])) {
        $duration = "3";
    }
    else
    {
        $duration = $options["block_duration"];
    }

    $blocked_ips = get_option('wp_security_blocked_ips', []);
    $blocked_ips[$ip] = time() + $duration;
    update_option('wp_security_blocked_ips', $blocked_ips);
}

function sec_unblock_ip($ip) {
    $blocked_ips = get_option('wp_security_blocked_ips', []);
    unset($blocked_ips[$ip]);
    update_option('wp_security_blocked_ips', $blocked_ips);
}

function sec_is_ip_blocked($ip) {
    $blocked_ips = get_option('wp_security_blocked_ips', []);
    if (isset($blocked_ips[$ip])) {
        if ($blocked_ips[$ip] < time()) {
            sec_unblock_ip($ip);
            return false;
        }
        return true;
    }
    return false;
}

function sec_track_failed_login($username) {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["block_login"]) and $options["block_login"] == 1 ) {
        $ip = $_SERVER['REMOTE_ADDR'];

        if (sec_is_ip_blocked($ip)) {
            wp_die('Вы заблокированы. Попробуйте позже.');
        }

        $failed_logins = get_option('wp_security_failed_logins', []);
        if (! isset($failed_logins[$ip])) {
            $failed_logins[$ip] = 0;
        }

        $failed_logins[$ip]++;

        if (empty($options) or empty($options["max_login_attempts"])) {
            $max_attempts = "5";
        }
        else
        {
            $max_attempts = $options["max_login_attempts"];
        }

        if ($failed_logins[$ip] > $max_attempts) {
            sec_block_ip($ip);
            wp_die('Вы заблокированы за несколько неудачных попыток входа. Попробуйте позже.');
        }

        update_option('wp_security_failed_logins', $failed_logins);
    }
}

add_action('wp_login_failed', 'sec_track_failed_login');

/* Блокировка Входа */

/* ReCaptcha */

function sec_add_recaptcha() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["use_recaptcha"]) and $options["use_recaptcha"] == 1) {
        wp_enqueue_script(
            'recaptcha',
            'https://www.google.com/recaptcha/api.js',
            [],
            null,
            true
        );
    }
}

add_action('login_enqueue_scripts', 'sec_add_recaptcha');

function sec_add_recaptcha_to_register_form() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["use_recaptcha"]) and $options["use_recaptcha"] == 1) {
        echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($options["recaptcha_site_key"]) . '"></div>';
    }
}

add_action('register_form', 'sec_add_recaptcha_to_register_form');

function sec_verify_recaptcha($errors, $sanitized_user_login, $user_email) {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["use_recaptcha"]) and $options["use_recaptcha"] == 1) {
        if (isset($_POST['g-recaptcha-respone'])) {
            $recaptcha_response = sanitize_text_field($_POST['g-recaptcha-respone']);

            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                'body' => [
                    'secret' => $options["recaptcha_secret_key"],
                    'respone' => $recaptcha_response,
                ])
            );

            $respone_body = wp_remote_retrive_body($respone);
            $result = json_decode($response_body, true);

            if (empty($result['success']) || ! $result['success']) {
                $errors->add('recaptcha_failed', 'Пожалуйста, подтвердите, что вы не робот');
            }
        }
        else {
            $errors->add('recaptcha_missing', 'Пожалуйста, подтвердите, что вы не робот');
        }
    }

    return $errors;

}

add_filter('registrations_errors', 'sec_verify_recaptcha', 10, 3);

/* ReCaptcha */

function sec_block_unauthorized() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["block_unauthorized"]) and $options["block_unauthorized"] == 1) {
        if (is_author() && ! is_user_logged_in()) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit;
        }
    }
}

add_action('template_redirect', 'sec_block_unauthorized');

function sec_set_to_maintenance() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty ($options["set_to_maintenance"]) and $options["set_to_maintenance"] == 1) {
        if (!current_user_can('manage_options') && !is_admin()) {
            wp_redirect(home_url('maintenance'));
            exit;
        }
    }
}

add_action('template_redirect', 'sec_set_to_maintenance');

function sec_add_maintenance_page() {
    $page_title = 'Обслуживание';
    $page_content = 'Сайт находится на обслуживании';

    $maintenance_page = get_page_by_title($page_title);

    if (! $maintenance_page) {
        $maintenance_page_data = array(
            'post_title' => $page_title,
            'page_content' => $page_content,
            'post_status' => 'publish',
            'post_type' => 'page',
        );

        wp_insert_post($maintenance_page_data);
    }
}

add_action('init', 'sec_add_maintenance_page');

/* Реализация функций оптимизации */

/* XML-карта */
function opt_generate_xml_map() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty($options["generate_xml_map"]) and $options["generate_xml_map"] == 1) {
        $generate_xml_map = $options["generate_xml_map"];
        $options["generate_xml_map"] = 0;
        update_option('dbi_security_plugin_options', $options);

        $sitemap = '<?xml version="l.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/O.9">';

        $urls = opt_get_all_page_urls();

        foreach ($urls as $url) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . esc_url($url) . '</loc>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';

        $upload_dir = wp_upload_dir();
        $file = trailingslashit($upload_dir['basedir']) . 'sitemap.xml';

        file_put_contents($file, $sitemap);
    }
}

add_action( 'init', 'opt_generate_xml_map' );

function opt_get_all_page_urls() {
    $urls = array();

    $posts = get_posts(array(
        'post_type' => array('page', 'post'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));

    foreach ($posts as $post) {
        $urls[] = get_permalink($post->ID);
    }

    return $urls;
}
/* XML-карта */

function opt_generate_robots() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty($options["generate_robots"]) and $options["generate_robots"] == 1) {
        $generate_robots = $options["generate_robots"];
        $options["generate_robots"] = 0;
        update_option('dbi_security_plugin_options', $options);

        $robots_content = "User-agent: *\n";
        $robots_content .= "Disallow: /wp-admin/\n";
        $robots_content .= "Disallow: /wp-includes/\n";

        $upload_dir = wp_upload_dir();
        $file = trailingslashit($upload_dir['basedir']) . 'robots.txt';

        file_put_contents($file, $robots_content);
    }
}

add_action( 'init', 'opt_generate_robots' );

function opt_set_seo_metatags() {
    $options = get_option( 'dbi_security_plugin_options' );

    if (! empty($options) and ! empty($options["set_seo_metatags"]) and $options["set_seo_metatags"] == 1) {
        $title = wp_title('', false);

        $description = get_the_excerpt();

        $keywords = "";

        if (! empty($options["seo_keywords"])) {
            $keywords = $options["seo_keywords"];
        }

        $meta_tags = '<meta name="description" content="' . esc_attr($description) . '"/>' . "\n";
        $meta_tags .= '<meta name="keywords" content="' . esc_attr($keywords) . '"/>' . "\n";
        $meta_tags .= '<title>' . esc_html($title) . "</title>\n";

        echo $meta_tags;
    }
}

add_action('wp_head', 'opt_set_seo_metatags');

/* Блокировка Входа (Меню админа) */

function misc_blocked_ips() {
    add_menu_page(
        'Заблокированные IP',
        'Заблокированные IP',
        'manage_options',
        'blocked_ips',
        'misc_blocked_ips_page'
    );
}

function misc_blocked_ips_page() {
    $blocked_ips = get_option('wp_security_blocked_ips', []);

    echo '<h1>Заблокированные IP-адреса</h1>';
    echo '<table>';
    echo '<tr><th>IP-адрес</th><th>Заблокирован до</th><th>Действия</th></tr>';

    foreach ($blocked_ips as $ip => $blocked_until) {
        echo '<tr>';
        echo '<td>' . esc_html($ip) . '</td>';
        echo '<td>' . date('Y-m-d H:i:s', $blocked_until) . '</td>';
        echo '<td><a href="?page=blocked_ips&unblock_ip=' . $ip . '">Разблокировать IP</a></td>';
        echo '</tr>';
    }

    echo '</table>';

    if (isset($_GET['unblock_ip'])) {
        sec_unblock_ip(esc_html($_GET['unblock_ip']));
        echo '<div>IP-адрес ' . esc_html($_GET['unblock_ip']) . ' разблокирован.</div>';
    }
}

add_action('admin_menu', 'misc_blocked_ips');

/* Блокировка Входа (Меню админа) */

/* Стойкость пароля (Меню админа) */

function misc_password_strength() {
    add_menu_page(
        'Стойкость пароля',
        'Стойкость пароля',
        'manage_options',
        'password_strength',
        'misc_password_strength_page',
        'dashicons-shield',
        80
    );
}

add_action('admin_menu', 'misc_password_strength');

function misc_password_strength_page() {
    echo '<h1>Инструмент определения стойкости пароля</h1>';
    echo '<p>Введите пароль, чтобы рассчитать время необходимое для его взлома методом грубой силы.</p>';
    echo '<form method="post">';
    echo '<input type="text" name="password" placeholder="Введите пароль"/>';
    echo '<input type="submit" value="Проверить стойкость"/>';
    echo '</form>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
        $password = sanitize_text_field($_POST['password']);

        $time_to_crack = misc_calculate_time_to_crack($password);

        echo '<p>Примерное время, необходимое для взлома пароля: ' . esc_html($time_to_crack) . '</p>';
    }
}

function misc_calculate_time_to_crack($password) {
    $attempts_per_second = 1000000;
    $charset_size = 0;

    if (preg_match('/[a-z]/', $password)) {
        $charset_size += 26;
    }

    if (preg_match('/[A-Z]/', $password)) {
        $charset_size += 26;
    }

    if (preg_match('/[0-9]/', $password)) {
        $charset_size += 10;
    }

    if (preg_match('/[^a-zA-Z0-9]/', $password)) {
        $charset_size += 32;
    }

    $total_combinations = pow($charset_size, strlen($password));
    $second_to_crack = $total_combinations / $attempts_per_second;

    if ($second_to_crack < 60) {
        return 'меньше минуты';
    }
    else if ($second_to_crack < 3000) {
        return round($second_to_crack / 60, 2) . ' минут';
    }
    else if ($second_to_crack < 86400) {
        return round($second_to_crack / 3600, 2) . ' часов';
    }
    else {
        return round($second_to_crack / 86400, 2) . ' дней';
    }
}

/* Стойкость пароля (Меню админа) */

?>
