<?php

namespace App\Tests\_mock\Mock\core\legacy\Statistics;

use App\Module\Quotes\Statistics\Subpanels\SubPanelQuotesTotal;
use App\Tests\_mock\Helpers\core\legacy\Data\DBQueryResultsMocking;

/**
 * Class SubPanelQuotesTotalMock
 * @package Mock\Core\Legacy\Statistics
 */
class SubPanelQuotesTotalMock extends SubPanelQuotesTotal
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
                'from' => '',
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
