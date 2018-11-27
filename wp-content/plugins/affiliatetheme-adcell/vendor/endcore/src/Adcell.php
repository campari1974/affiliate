<?php

/**
 * Project      affiliatetheme-adcell
 * @author      api@adcell.de
 * @author      Giacomo Barbalinardo <info@ready24it.eu>
 * @copyright   2015
 */
namespace EcAdcell;

class Adcell {

    protected $user = ADCELL_USER;
    protected $password = ADCELL_PASS;

    /**
     * Url zur API
     *
     * @var string
     */
    protected $apiUrl = 'https://www.adcell.de/api/';

    /**
     * Version der API
     *
     * @var string
     */
    protected $apiVersion = 'v2';

    /**
     * Liefert die BasisUrl aus
     *
     * @return string
     */
    protected function getApiBaseUrl() {
        return $this->apiUrl . $this->apiVersion;
    }

    /**
     * Dekodierung, hier im Beispiel nur von json zu stdclass
     *
     * @param  string $data Daten
     * @param  string $format (Optional) Format
     * @return \stdClass
     */
    protected function decode($data, $format = 'json'){
        if ($format == 'json') {
            return json_decode($data);
        }
    }

    /**
     * Startet einen Request und liefert Daten als stdClass zurück
     *
     * @param  string $service ServiceName
     * @param  string $call MethodenName
     * @param  array $options Optionen
     * @return \stdClass
     * @throws \Exception
     */
    protected function request($service, $call, $options) {
        $url = $this->getApiBaseUrl() . '/' . $service . '/' . $call . '?';

        foreach ($options as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $url .= '&' . trim($key) . '[]=' . $item;
                }
            } else {
                $url .= '&' . trim($key) . '=' . $value;
            }
        }

        //var_dump($url);

        $data = file_get_contents($url);
        if (strlen($data) == 0) {
            throw new \Exception('invalid result received');
        }

        $data = $this->decode($data);
        if ($data->status == 200) {
            return $data;
        } else {
            throw new \Exception($data->message);
        }
    }

    /**
     * Ermittlung des Tokens
     *
     * @return string
     */
    public function getToken() {
        $data = $this->request(
            'user',
            'getToken',
            array(
                'userName' => $this->user,
                'password' => $this->password,
            )
        );

        return $data->data->token;
    }

    /**
     * @return array
     */
    protected function getTokenAsParamArray() {
        return array(
            'token' => $this->getToken()
        );
    }

    /**
     * BeispielRequest für Schnittstelle user/whoami
     *
     * @return stdClass
     * @throws \Exception
     */
    public function whoAmI(){
        return $this->request(
            'user',
            'whoami',
            $this->getTokenAsParamArray()
        );
    }

    /**
     * @return ProgramList
     * @throws \Exception
     */
    public function getProgramAccepted()
    {
        $options = array(
            'affiliateStatus' => 'accepted',
            'rows' => 999
        );

        return new ProgramList($this->request(
            'affiliate/program',
            'export',
            array_merge(
                $options,
                $this->getTokenAsParamArray()
            )
        ));
    }

    /**
     * @param array $options
     * @return ProgramList
     * @throws \Exception
     */
    public function getPromotionTypeCsv(array $options = array())
    {
        return new PromotionList($this->request(
            'affiliate/promotion',
            'getPromotionTypeCsv',
            array_merge(
                $options,
                $this->getTokenAsParamArray()
            )
        ));
    }

    public function getPromotionTypeCsvById($id)
    {
        $list = $this->getPromotionTypeCsv(
            array(
                'promotionId' => $id
            )
        );

        if ($list->count() == 1) {
            return $list->current();
        }
        return false;
    }


}

?>