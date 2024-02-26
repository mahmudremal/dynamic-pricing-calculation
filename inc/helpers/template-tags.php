<?php
/**
 * Custom template tags for the theme.
 *
 * @package TeddyBearCustomizeAddon
 */
if( ! function_exists( 'is_FwpActive' ) ) {
  function is_FwpActive( $opt ) {
    if( ! defined( 'DPC_OPTIONS' ) ) {return false;}
    return ( isset( DPC_OPTIONS[ $opt ] ) && DPC_OPTIONS[ $opt ] == 'on' );
  }
}
if( ! function_exists( 'get_FwpOption' ) ) {
  function get_FwpOption( $opt, $def = false ) {
    if( ! defined( 'DPC_OPTIONS' ) ) {return false;}
    return isset( DPC_OPTIONS[ $opt ] ) ? DPC_OPTIONS[ $opt ] : $def;
  }
}