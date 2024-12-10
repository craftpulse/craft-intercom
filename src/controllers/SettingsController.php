<?php

namespace craftpulse\intercom\controllers;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\Controller;

use craftpulse\intercom\Intercom;
use yii\web\Response;

class SettingsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        $this->requireAdmin();

        return parent::beforeAction($action);
    }

    /**
     * @return Response|null
     */
    public function actionEdit(): ?Response
    {
        // Ensure they have permission to edit the plugin settings
        $currentUser = Craft::$app->getUser()->getIdentity();
        if (!$currentUser->can('intercom:settings')) {
            throw new ForbiddenHttpException('You do not have permission to edit the Intercom settings.');
        }
        $general = Craft::$app->getConfig()->getGeneral();
        if (!$general->allowAdminChanges) {
            throw new ForbiddenHttpException('Unable to edit Intercom plugin settings because admin changes are disabled in this environment.');
        }

        // Edit the plugin settings
        $variables = [];
        $pluginName = 'Intercom';
        $templateTitle = Craft::t('intercom', 'Plugin settings');

        $variables['fullPageForm'] = true;
        $variables['pluginName'] = $pluginName;
        $variables['title'] = $templateTitle;
        $variables['docTitle'] = "{$pluginName} - {$templateTitle}";
        $variables['crumbs'] = [
            [
                'label' => $pluginName,
                'url' => UrlHelper::cpUrl('intercom'),
            ],
            [
                'label' => $templateTitle,
                'url' => UrlHelper::cpUrl('intercom/plugin'),
            ],
        ];
        $variables['settings'] = Intercom::$plugin->settings;

        return $this->renderTemplate('intercom/_settings', $variables);
    }

    /**
     * Saves the plugin settings
     */
    public function actionSave(): ?Response
    {
        // Ensure they have permission to edit the plugin settings
        $currentUser = Craft::$app->getUser()->getIdentity();
        if (!$currentUser->can('intercom:settings')) {
            throw new ForbiddenHttpException('You do not have permission to edit the Intercom settings.');
        }
        $general = Craft::$app->getConfig()->getGeneral();
        if (!$general->allowAdminChanges) {
            throw new ForbiddenHttpException('Unable to edit Intercom plugin settings because admin changes are disabled in this environment.');
        }

        // Save the plugin settings
        $this->requirePostRequest();
        $pluginHandle = Craft::$app->getRequest()->getRequiredBodyParam('pluginHandle');
        $plugin = Craft::$app->getPlugins()->getPlugin($pluginHandle);
        $settings = Craft::$app->getRequest()->getBodyParam('settings', []);

        if ($plugin === null) {
            throw new NotFoundHttpException('Plugin not found');
        }

        if (!Craft::$app->getPlugins()->savePluginSettings($plugin, $settings)) {
            Craft::$app->getSession()->setError(Craft::t('app', "Couldn't save plugin settings."));

            // Send the redirect back to the template
            /** @var UrlManager $urlManager */
            $urlManager = Craft::$app->getUrlManager();
            $urlManager->setRouteParams([
                'plugin' => $plugin,
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('app', 'Plugin settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
