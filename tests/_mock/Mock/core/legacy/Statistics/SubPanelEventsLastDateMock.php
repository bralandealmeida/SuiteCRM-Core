<?php

namespace App\Tests\_mock\Mock\core\legacy\Statistics;

use App\Module\Events\Statistics\Subpanels\SubPanelEventsLastDate;
use App\Tests\_mock\Helpers\core\legacy\Data\DBQueryResultsMocking;

/**
 * Class SubPanelEventsLastDateMock
 * @package App\Tests\_mock\Mock\core\legacy\Statistics
 */
class SubPanelEventsLastDateMock extends SubPanelEventsLastDate
{
    use DBQueryResultsMocking;

    /**
     * @inheritDoc
     */
    public function getQueries(string $parentModule, string $parentId, string $subpanel): array
    {
        return [
            [
                'select' => '',
                'where' => '',
                'order_by' => '',
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function fetchRow(string $query): array
    {
        return $this->getMockQueryResults();
    }

    /**
     * @inheritDoc
     */
    protected function startLegacyApp(string $currentModule = ''): void
    {
    }
}
