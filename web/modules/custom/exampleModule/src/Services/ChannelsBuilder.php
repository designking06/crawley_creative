<?php

namespace Drupal\example_lineup\Services;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\example_location\Services\LocationBuilder;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a Channel Lineup Block.
 *
 * @Block(
 *   id = "channel_lineup",
 *   admin_label = @Translation("Channel Lineup"),
 *   category = @Translation("Channel Lineup"),
 * )
 */
class ChannelsBuilder {

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected EntityTypeManagerInterface $entityTypeManager;

    /**
     * Client from Guzzle.
     *
     * @var \GuzzleHttp\Client
     */
    protected Client $client;

    /**
     * Form builder.
     *
     * @var \Drupal\Core\Form\FormBuilderInterface
     */
    protected FormBuilderInterface $formBuilder;

    /**
     * Location builder.
     *
     * @var \Drupal\example_location\Services\LocationBuilder
     */
    protected LocationBuilder $location;

    /**
     * Request Stack.
     *
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $request;

    /**
     * ChannelsBuilder constructor.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityManager
     *   The entity type manager.
     * @param \GuzzleHttp\Client $client
     *   Guzzle client.
     * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
     *   Form builder.
     * @param \Drupal\example_location\Services\LocationBuilder $location
     *   LocationBuilder.
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     *   Request Stack.
     */
    public function __construct(EntityTypeManagerInterface $entityManager,
                                Client $client,
                                FormBuilderInterface $formBuilder,
                                LocationBuilder $location,
                                RequestStack $request) {
        $this->entityTypeManager = $entityManager;
        $this->client = $client;
        $this->formBuilder = $formBuilder;
        $this->location = $location;
        $this->request = $request;
    }

    /**
     * Generate form filters.
     *
     * @return int|mixed|null
     *   Return form.
     */
    public function lineupFiltersBuilder() {
        return $this->formBuilder->getForm('Drupal\example_lineup\Form\FiltersForm');
    }

    /**
     * Channel Lineup Builder data.
     *
     * @return int|mixed|null
     *   Return all render array.
     */
    public function lineupTableBuilder() {
        // Get cookie value.
        $cookie = $this->request->getCurrentRequest()->cookies->get('location');
        // Get lineup id from location.
        try {
            $lineup_id = $this->location->getLocationLineupId($cookie);
        }
        catch (PluginNotFoundException $e) {
            return FALSE;
        }
        catch (InvalidPluginDefinitionException $e) {
            return FALSE;
        }

        $rows = [];

        if (!empty($lineup_id)) {

            // Get data from api.
            $data = $this->getApiData($lineup_id);

            if ($data) {
                foreach ($data as $row) {

                    if (!empty($row['Limited'])) {
                        $row['Limited'] = '.';
                    }
                    else {
                        $row['Limited'] = '';
                    }
                    if (!empty($row['Basic'])) {
                        $row['Basic'] = '.';
                    }
                    else {
                        $row['Basic'] = '';
                    }
                    // Build rows.
                    $rows[] = [
                        'Network' => $row['channelName'],
                        'Channel' => $row['channelNumber'],
                        'HD Channel' => $row['hdChannelNumber'],
                    ];
                }
            }
        }
        // Build headers for table.
        $headers = [
            t('Network'),
            t('Channel'),
            t('HD Channel'),
        ];

        $table = [
            '#theme' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#attributes' => [
                'id' => 'table-lineup',
                'class' => [
                    'display',
                ],
            ],
        ];

        return render($table);
    }

    /**
     * Request Api data.
     *
     * @param mixed $id
     *   Id of the channel lineup.
     *
     * @return mixed
     *   Return data.
     */
    private function getApiData($id) {
        $data = [];
        $uri = 'https://uri.com/lineups/' . $id;
        // Request to api.
        $response = $this->client->get($uri, [
            'headers' => [
                'x-api-key' => 'examplekeyhash',
            ],
        ]);

        if ($response) {
            $data = Json::decode($response->getBody());
        }

        return $data;
    }

}
