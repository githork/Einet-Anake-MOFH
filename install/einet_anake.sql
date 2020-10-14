-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-07-2020 a las 01:48:12
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `einet_anake`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_cpanel`
--

CREATE TABLE IF NOT EXISTS `anak_u_cpanel` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_client` int(11) NOT NULL,
  `cp_user` varchar(20) NOT NULL,
  `cp_password` varchar(70) NOT NULL,
  `cp_name` varchar(15) NOT NULL,
  `cp_email` varchar(40) NOT NULL,
  `cp_domain` text NOT NULL,
  `cp_active` int(1) NOT NULL DEFAULT '0',
  `cp_plan` int(1) NOT NULL DEFAULT '1',
  `cp_over` int(10) NOT NULL,
  `cp_ip` varchar(18) NOT NULL,
  `cp_date` int(10) NOT NULL,
  PRIMARY KEY (`cp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_mensajes`
--

CREATE TABLE IF NOT EXISTS `anak_u_mensajes` (
  `mp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_to` int(11) NOT NULL,
  `mp_from` int(11) NOT NULL,
  `mp_answer` int(1) NOT NULL DEFAULT '0',
  `mp_read_to` int(1) NOT NULL DEFAULT '0',
  `mp_read_from` int(1) NOT NULL DEFAULT '1',
  `mp_read_mon_to` int(1) NOT NULL DEFAULT '0',
  `mp_read_mon_from` int(1) NOT NULL DEFAULT '1',
  `mp_del_to` int(1) NOT NULL DEFAULT '0',
  `mp_del_from` int(1) NOT NULL DEFAULT '0',
  `mp_subject` varchar(50) NOT NULL,
  `mp_preview` varchar(75) NOT NULL,
  `mp_date` int(10) NOT NULL,
  PRIMARY KEY (`mp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_miembros`
--

CREATE TABLE IF NOT EXISTS `anak_u_miembros` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_activo` int(1) NOT NULL DEFAULT '0',
  `user_baneado` int(1) NOT NULL DEFAULT '0',
  `user_nick` varchar(35) NOT NULL,
  `user_name` varchar(35) NOT NULL,
  `user_password` varchar(80) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_online` int(10) NOT NULL DEFAULT '0',
  `user_ip` varchar(18) NOT NULL,
  `user_social_login` text NOT NULL,
  `user_seguidores` int(11) NOT NULL,
  `user_rango` int(3) NOT NULL DEFAULT '3',
  `user_antiFlood` int(1) NOT NULL DEFAULT '0',
  `user_pin` varchar(9) NOT NULL,
  `user_registro` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_monitor`
--

CREATE TABLE IF NOT EXISTS `anak_u_monitor` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `obj_user` int(11) NOT NULL,
  `obj_uno` int(11) NOT NULL DEFAULT '0',
  `obj_dos` int(11) NOT NULL DEFAULT '0',
  `obj_tres` int(11) NOT NULL DEFAULT '0',
  `not_type` int(2) NOT NULL,
  `not_date` int(10) NOT NULL,
  `not_total` int(2) NOT NULL DEFAULT '1',
  `not_menubar` int(1) NOT NULL DEFAULT '2',
  `not_monitor` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_perfil`
--

CREATE TABLE IF NOT EXISTS `anak_u_perfil` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_dia` int(2) NOT NULL,
  `p_mes` int(2) NOT NULL,
  `p_year` int(4) NOT NULL,
  `p_pais` varchar(2) NOT NULL,
  `p_sexo` int(1) NOT NULL DEFAULT '0',
  `p_imagen` int(1) NOT NULL DEFAULT '0',
  `p_cover` text NOT NULL,
  `p_web` varchar(35) NOT NULL,
  `p_config` varchar(100) NOT NULL DEFAULT 'a:3:{s:1:"m";s:1:"5";s:2:"mf";i:5;s:3:"rmp";s:1:"5";}',
  `p_mensaje` varchar(200) NOT NULL,
  `p_monitor` varchar(300) NOT NULL DEFAULT 'a:4:{s:2:"f1";i:0;s:4:"ntfs";i:0;s:4:"msgs";i:0;s:5:"sound";i:0;}',
  `p_idioma` int(5) NOT NULL DEFAULT '23',
  `p_limite` int(3) NOT NULL DEFAULT '3',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_rangos`
--

CREATE TABLE IF NOT EXISTS `anak_u_rangos` (
  `id_rango` int(3) NOT NULL AUTO_INCREMENT,
  `r_nombre` varchar(32) NOT NULL,
  `r_color` varchar(6) NOT NULL DEFAULT '171717',
  `r_imagen` varchar(32) NOT NULL DEFAULT 'new.png',
  `r_puntos` int(5) NOT NULL,
  `r_allows` varchar(1000) NOT NULL,
  `r_type` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_rango`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `anak_u_rangos`
--

INSERT INTO `anak_u_rangos` (`id_rango`, `r_nombre`, `r_color`, `r_imagen`, `r_puntos`, `r_allows`, `r_type`) VALUES
(1, 'Administrator', 'FF0000', 'rank_1', 0, 'a:4:{s:5:"oa_ad";s:2:"on";s:4:"goaf";s:1:"5";s:5:"gopfp";s:2:"20";s:5:"gopfd";s:2:"50";}', 0),
(2, 'Moderator', 'FFBF00', 'rank_2', 0, 'a:4:{s:5:"oa_mo";s:2:"on";s:4:"goaf";s:2:"15";s:5:"gopfp";s:2:"18";s:5:"gopfd";s:2:"30";}', 0),
(3, 'Newbie', '', 'rank_3', 0, 'a:12:{s:4:"godp";s:2:"on";s:4:"gopp";s:2:"on";s:5:"gopcp";s:2:"on";s:5:"govpp";s:2:"on";s:5:"govpn";s:2:"on";s:5:"goepc";s:2:"on";s:5:"godpc";s:2:"on";s:4:"gopf";s:2:"on";s:5:"gopcf";s:2:"on";s:4:"goaf";s:2:"20";s:5:"gopfp";s:1:"5";s:5:"gopfd";s:1:"5";}', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_respuestas`
--

CREATE TABLE IF NOT EXISTS `anak_u_respuestas` (
  `mr_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) NOT NULL,
  `mr_from` int(11) NOT NULL,
  `mr_body` text NOT NULL,
  `mr_ip` varchar(15) NOT NULL,
  `mr_date` int(10) NOT NULL,
  PRIMARY KEY (`mr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_seguidores`
--

CREATE TABLE IF NOT EXISTS `anak_u_seguidores` (
  `cont_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_user` int(11) NOT NULL,
  `s_sigue` int(11) NOT NULL,
  `s_type` int(1) NOT NULL,
  `s_date` int(10) NOT NULL,
  PRIMARY KEY (`cont_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_u_sessions`
--

CREATE TABLE IF NOT EXISTS `anak_u_sessions` (
  `session_id` varchar(32) NOT NULL,
  `session_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `session_ip` varchar(18) NOT NULL,
  `session_time` int(10) unsigned NOT NULL DEFAULT '0',
  `session_autologin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`),
  KEY `session_user_id` (`session_user_id`),
  KEY `session_time` (`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_blacklist`
--

CREATE TABLE IF NOT EXISTS `anak_w_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `b_type` int(1) NOT NULL,
  `b_value` varchar(50) NOT NULL,
  `b_reason` varchar(120) NOT NULL,
  `b_author` int(11) NOT NULL,
  `b_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_config`
--

CREATE TABLE IF NOT EXISTS `anak_w_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `antiFlood` int(1) NOT NULL DEFAULT '0',
  `tema_id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `description` varchar(60) NOT NULL,
  `total_visitas` int(11) NOT NULL,
  `total_online` int(11) NOT NULL,
  `activos` text NOT NULL,
  `fecha_limpieza` int(11) NOT NULL,
  `user_activo` int(2) NOT NULL DEFAULT '15',
  `web_on` int(1) NOT NULL DEFAULT '0',
  `web_mensaje` text NOT NULL,
  `web_over` int(11) NOT NULL DEFAULT '0',
  `access_mod` int(1) NOT NULL DEFAULT '0',
  `bienvenida` int(1) NOT NULL DEFAULT '0',
  `bienvenida_msg` varchar(500) NOT NULL,
  `pub_300` text NOT NULL,
  `pub_468` text NOT NULL,
  `pub_160` text NOT NULL,
  `pub_728` text NOT NULL,
  `cp` text NOT NULL,
  `api_services` text NOT NULL,
  `smtp` text NOT NULL,
  `version` varchar(16) NOT NULL,
  `version_code` varchar(16) NOT NULL,
  `web_censurar` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `anak_w_config`
--

INSERT INTO `anak_w_config` (`id`, `antiFlood`, `tema_id`, `titulo`, `description`, `total_visitas`, `total_online`, `activos`, `fecha_limpieza`, `user_activo`, `web_on`, `web_mensaje`, `web_over`, `access_mod`, `bienvenida`, `bienvenida_msg`, `pub_300`, `pub_468`, `pub_160`, `pub_728`, `cp`, `api_services`, `smtp`, `version`, `version_code`, `web_censurar`) VALUES
(1, 0, 1, '', '', 0, 1, 'a:17:{s:8:"cod_type";s:5:"utf-8";s:10:"log_active";i:0;s:10:"reg_active";i:0;s:11:"live_active";i:1;s:9:"live_time";i:30000;s:9:"live_hide";i:19800;s:12:"email_active";i:1;s:10:"pub_active";i:1;s:10:"val_cuenta";i:0;s:11:"upload_type";i:0;s:13:"upload_server";s:5:"imgur";s:9:"reg_limit";i:1000;s:14:"captcha_active";i:1;s:13:"allow_sess_ip";i:1;s:10:"allow_edad";i:13;s:8:"max_ntfs";i:99;s:9:"max_posts";s:2:"20";}', 0, 15, 0, 'Routine maintenance mode.', 0, 0, 1, 'Hello young, [usuario] [welcome]  a [b][web][/b] thank you for registering with us, from now on you can start enjoying all the services at [web], pass it on super and the greatest of successes to you.', '', '', '', '', '', '', '', 'Anake v5.2.0.30', 'Anake_5_2_0_30', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_contacts`
--

CREATE TABLE IF NOT EXISTS `anak_w_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `hash_time` int(10) NOT NULL,
  `hash_type` int(1) NOT NULL,
  `hash_code` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_ips`
--

CREATE TABLE IF NOT EXISTS `anak_w_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `user_log` int(1) NOT NULL DEFAULT '0',
  `user_position` text NOT NULL,
  `user_info` text NOT NULL,
  `ip_type` int(1) NOT NULL DEFAULT '0',
  `ip_user` varchar(15) NOT NULL,
  `ip_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_noticias`
--

CREATE TABLE IF NOT EXISTS `anak_w_noticias` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `not_body` text NOT NULL,
  `not_autor` int(11) NOT NULL,
  `not_date` int(10) NOT NULL,
  `not_active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_suspendido`
--

CREATE TABLE IF NOT EXISTS `anak_w_suspendido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obj` int(11) NOT NULL,
  `type_obj` int(1) NOT NULL,
  `type_susp` int(1) NOT NULL,
  `user_report` int(11) NOT NULL DEFAULT '0',
  `s_reason` int(2) NOT NULL,
  `s_extra` varchar(300) NOT NULL,
  `s_date` int(11) NOT NULL,
  `s_termina` int(11) NOT NULL,
  `s_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_temas`
--

CREATE TABLE IF NOT EXISTS `anak_w_temas` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(15) NOT NULL,
  `t_url` varchar(18) NOT NULL,
  `t_path` varchar(15) NOT NULL,
  `t_copy` varchar(15) NOT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `anak_w_temas`
--

INSERT INTO `anak_w_temas` (`t_id`, `t_name`, `t_url`, `t_path`, `t_copy`) VALUES
(1, 'Anake v1.1', '/temas/default', 'default', 'Einet 2020.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anak_w_upload`
--

CREATE TABLE IF NOT EXISTS `anak_w_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_type` int(1) NOT NULL,
  `u_control` int(11) NOT NULL,
  `u_imagen` varchar(250) NOT NULL,
  `u_delete` varchar(250) NOT NULL,
  `u_date` int(11) NOT NULL,
  `u_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
