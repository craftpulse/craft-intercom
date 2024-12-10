<?php
/**
 * Supercontrol plugin for Craft CMS
 *
 * Sync availabilities coming from Supercontrol
 *
 * @link      https://craftpulse.com
 * @copyright Copyright (c) 2024 craftpulse
 */

namespace craftpulse\intercom\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

class SettingsModel extends Model
{
    /**
     * @var string the Base URL for Intercom
     */
    public string $baseUrl = '';

    /**
     * @var string the App ID for Intercom
     */
    public string $appId = '';

    /**
     * @var bool if we should include the company information
     */
    public bool $includeCompanyData = false;

    /**
     * @var string the company ID for the company object
     */
    public ?string $companyId = null;

    /**
     * @var string the name for the company object
     */
    public ?string $companyName = null;

    /**
     * @var string the plan for the company object
     */
    public ?string $plan = null;

    /**
     * @var string the industry for the company object
     */
    public ?string $industry = null;

    /**
     * @var string the website for the company object
     */
    public ?string $website = null;

    protected function defineBehaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => ['appId', 'baseUrl', 'companyId', 'companyName', 'plan', 'industry', 'website', 'includeCompanyData'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return [
            [['appId', 'baseUrl', 'includeCompanyData'], 'required'],
        ];
    }
}
