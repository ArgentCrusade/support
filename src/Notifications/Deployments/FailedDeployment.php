<?php

namespace ArgentCrusade\Support\Notifications\Deployments;

class FailedDeployment extends DeploymentResultNotification
{
    public function content()
    {
        return trans('support::deployments.notifications.failed', $this->replacements());
    }
}
