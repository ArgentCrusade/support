<?php

namespace ArgentCrusade\Support\Notifications\Deployments;

class SuccessDeployment extends DeploymentResultNotification
{
    public function content()
    {
        return trans('support::deployments.notifications.success', $this->replacements());
    }
}
