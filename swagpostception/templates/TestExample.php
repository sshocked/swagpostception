<?php

//namespace %namespace%;

/**
 * Тестирование Merchandising API
 */
class Cest
{

    /**
     * @inheritdoc
     *
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        if ($this->_initCompleted) {
            return true;
        }

        $this->_initCompleted = true;
    }

    /**
     * @param ApiTester $I
     */
    public function CreateSortApiTest(ApiTester $I)
    {
        $I->wantTo("Тестирование API создания сортировки");

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/merchandising', [
            'categoryId' => 9881,
            'brandId'    => '1395280,1490803',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
    }

    /**
     * @param ApiTester $I
     */
    public function AddProductSequenceApiTest(ApiTester $I)
    {
        $I->wantTo("Тестирование API добавления товаров в начало сортировки контекста");

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/merchandising/add_product_sequence', [
            'context'  => $this->_context,
            'products' => $this->_contextItemsId,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
        $I->seeResponseJsonMatchesXpath('//changedItems/*');
    }

    /**
     * @param ApiTester $I
     */
    public function ChangeItemsApiTest(ApiTester $I)
    {
        $I->wantTo("Тестирование API изменения мест товаров на одной странице");

        $I->comment("Перемещение товара с большей сортировки на меньщую (справа налево)");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/merchandising/change', [
            'context' => $this->_context,
            'idFrom'  => $this->_contextItemsId[2],
            'idTo'    => $this->_contextItemsId[1],
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
        $I->seeResponseJsonMatchesXpath('//changedItems/*');

        $I->comment("Перемещение товара с меньшей сортировки на большую (слева направо)");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/merchandising/change', [
            'context' => $this->_context,
            'idFrom'  => $this->_contextItemsId[1],
            'idTo'    => $this->_contextItemsId[2],
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
        $I->seeResponseJsonMatchesXpath('//changedItems/*');
    }

    /**
     * @param ApiTester $I
     */
    public function AddNewItemApiTest(ApiTester $I)
    {
        $I->wantTo("Тестирование API добавления нового товара");

        $I->comment("Добавление нового товара");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/merchandising', [
            'context'     => $this->_context,
            'insertedId'  => 1235146,
            'productIdTo' => $this->_contextItemsId[0],
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
        $I->seeResponseJsonMatchesXpath('//products/*');
    }

    /**
     * @param ApiTester $I
     */
    public function DeleteProductApiTest(ApiTester $I)
    {
        $id = $this->_contextItemsId[0];

        $I->wantTo("Тестирование API удаления товара из контекста");

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE("/merchandising/$this->_context/$id");
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
    }

    /**
     * @param ApiTester $I
     */
    public function DeleteContextApiTest(ApiTester $I)
    {
        $I->wantTo("Тестирование API удаления контекста");

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE("/merchandising/$this->_context");
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Данные сохранены успешно']);
    }
}
