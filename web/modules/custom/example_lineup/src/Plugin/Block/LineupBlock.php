<?php

namespace Drupal\example_lineup\Plugin\Block;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\example_lineup\Services\ChannelsBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Popup Block.
 *
 * @Block(
 *   id = "lineup",
 *   admin_label = @Translation("Channel Lineup"),
 *   category = @Translation("Channel Lineup"),
 * )
 */
class LineupBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * Location Builder.
     *
     * @var \Drupal\example_lineup\Services\ChannelsBuilder
     */
    protected ChannelsBuilder $lineupBuilder;

    /**
     * LocationBlock constructor.
     *
     * @param array $configuration
     *   Configuration param.
     * @param mixed $plugin_id
     *   Plugin id param.
     * @param mixed $plugin_definition
     *   Plugin definition param.
     * @param \Drupal\example_lineup\Services\ChannelsBuilder $lineupBuilder
     *   Location builder instance.
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, ChannelsBuilder $lineupBuilder) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->lineupBuilder = $lineupBuilder;
    }

    /**
     * Create static function.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   Container param.
     * @param array $configuration
     *   Configuration param.
     * @param mixed $plugin_id
     *   Plugin id param.
     * @param mixed $plugin_definition
     *   Plugin definition param.
     *
     * @return \Drupal\example_lineup\Plugin\Block\LineupBlock|static
     *   Returns container with service.
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static($configuration, $plugin_id, $plugin_definition, $container->get('example_lineup.channels'));
    }

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
        return ['label_display' => FALSE];
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
        // Get content to be render.
        try {
            $table = $this->lineupBuilder->lineupTableBuilder();
            $filters = $this->lineupBuilder->lineupFiltersBuilder();
        }
        catch (PluginNotFoundException $e) {
            return FALSE;
        }

        return [
            '#theme' => 'block__lineupblock',
            '#table' => $table,
            '#filters' => $filters,
            '#cache' => [
                'max-age' => 0,
            ],
        ];

    }

}
