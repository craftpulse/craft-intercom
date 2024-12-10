<?php

namespace craftpulse\intercom\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use craft\helpers\Html as HtmlHelper;
use craft\web\View;

use craftpulse\intercom\Intercom;

class IntercomService extends Component
{
    // Public Methods
    // =========================================================================

    public function hasBotAccess(): bool
    {
        return Craft::$app->getUser()->checkPermission('intercom:bot-access') && Craft::$app->getRequest()->getIsSiteRequest();
    }

    public function renderChatBot(): string {
        $settings = Intercom::$plugin->settings;
        $currentUser = Craft::$app->getUser()->getIdentity();

        $variables['settings'] = [
            'baseUrl' => App::parseEnv($settings->baseUrl),
            'appId' => App::parseEnv($settings->appId),
            'name' => $currentUser->friendlyName,
            'email' => $currentUser->email,
            'createdAt' => $currentUser->dateCreated,
            'company' => [
                'includeData' => App::parseEnv($settings->includeCompanyData),
                'id' => App::parseEnv($settings->companyId),
                'name' => App::parseEnv($settings->companyName),
                'plan' => App::parseEnv($settings->plan),
                'website' => App::parseEnv($settings->website),
                'industry' => App::parseEnv($settings->industry),
            ]
        ];

        $currentMode = Craft::$app->getView()->getTemplateMode();
        // Set mode to CP Mode so it fetches the correct template to include
        Craft::$app->getView()->setTemplateMode(View::TEMPLATE_MODE_CP);

        $html = Craft::$app->getView()->renderTemplate('intercom/integrations/chatbot', $variables);

        Craft::$app->getView()->setTemplateMode($currentMode);

        return $html;
    }
}
