<?php

namespace Royl\WpThemeBase\Wp;

/**
 * WordPress Tools - Taxonomy
 *
 * This class is for doing WordPress-related things in code that we do
 * commonly, such as creating a new post type or creating an image attachment.
 *
 * @package     WpThemeBase
 * @subpackage  Wp
 * @author      Tim Shaw
 * @author      Nitish Narala
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Taxonomy
{
	/**
	 * Return list of taxonomy terms for $taxonomy
	 * @param  string The taxonomy to use to build the list
	 * @return array  array of terms [ slug => term ] 
	 */
	public static function list($taxonomy) {
	    $terms = get_terms( [
	        'taxonomy' => $taxonomy,
	        'hide_empty' => true
	    ]);
    
	    $ret = [];
	    foreach ( $terms as $term ) {
	        $ret[ $term->slug ] = $term->name;
	    }
    
	    return $ret;	
	}
	
    /**
     * This safely checks if a taxonomy term exists and creates it if not.
     *
     * @param   string  $termName       Name (not slug) of the term to create
     * @param   string  $taxonomy       Name of the taxonomy to check
     * @param   string  $parent         Optional Parent term ID to put term under
     * @param   string  $description    Optional Tax term description
     * @return  int     Returns the term ID of the found/created term or null
     **/
    public static function upsertTaxonomyTerm($termName, $taxonomy, $parent = 0, $description = null)
    {
        $category = term_exists($termName, $taxonomy, $parent);

        if (!$category) {
            $category = wp_insert_term($termName, $taxonomy, array(
                'parent'      => $parent,
                'description' => $description
            ));
        }

        if (!is_wp_error($category)) {
            return ((int) $category['term_id']);
        } else {
            if (defined(WP_DEBUG) && WP_DEBUG) {
                trigger_error($category->get_error_message(), E_USER_WARNING);
            }
        }

        // Well something went wrong
        return null;
    }

    /**
     * Clear all terms from a taxonomy.  If custom taxonomy is passed, will
     * clear that, otherwise will simple clear basic categories (these use
     * different functions).
     *
     * @param string $taxonomy Name of custom taxonomy to clear
     * @return bool Returns true/false on clear success/fail
     **/
    public static function flushTaxonomy($taxonomy = null)
    {
        if ($taxonomy) {
            $terms = get_terms($taxonomy, array('hide_empty' => false));
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, $taxonomy);
            }

            echo 'Deleted all ' . $taxonomy . ' terms.' . "\n";
        } else {
            $terms = get_categories(array('hide_empty' => 0));
            foreach ($terms as $term) {
                wp_delete_category($term->term_id);
            }

            echo 'Deleted all category terms.' . "\n";
        }

        // Return true/false based on whether or no there were any terms to
        // clear
        return !empty($terms);
    }
}
