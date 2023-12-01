<?php

namespace Drupal\nbl_blocks\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a 'Landing Page Feature' Block.
 *
 * @Block(
 *   id = "landing_page_feature",
 *   admin_label = @Translation("Landing Page Feature"),
 *   category = @Translation("Landing Page Feature"),
 * )
 */
class LandingPageFeature extends BlockBase implements BlockPluginInterface {
    public function build() {
        $markup = ''; // make sure we return something, even if blank

        // $node = Drupal::entityTypeManager()->getStorage('node');
        $node = \Drupal::routeMatch()->getParameter('node');
        if($node->getEntityType() == 'landing_page') { //check to make sure we're actually on a landing page

            $markup = Drupal::service('renderer')->render($node->field_carousel_feature->view('default'));

        }
       return [
            '#type' => 'markup',
            '#markup' => $markup,
        ];
        //end build.
    }

    function cca_module_views_pre_view(&$view, &$display_id, &$args)
    {

        if ($view->name == 'event_list' && $display_id == 'block_8') {
            $node = \Drupal::routeMatch()->getParameter('node');

            if ($node->bundle() == 'landing_page') {
                $event_cats = array(
                    $node->get('field_age_group')->value(),
                    $node->get('field_event_type')->value(),
                    $node->get('field_location')->value()
                );
                foreach ($event_cats as $cat_type) {
                    if (isset($cat_type['und'])) {
                        foreach ($cat_type['und'] as $cat) {
                            $args[] = $cat['tid'];
                        }
                    }
                }
            }
        }
    }
        //end module.
}
