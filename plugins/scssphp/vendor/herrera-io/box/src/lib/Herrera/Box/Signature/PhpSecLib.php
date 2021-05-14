<?php

namespace Herrera\Box\Signature;

use Crypt_RSA;

/**
 * Uses the phpseclib library to verify a signature.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class PhpSecLib extends AbstractPublicKey
{
    /**
     * @see VerifyInterface::verify
     */
    public function verify($signature)
    {
        $rsa = new Crypt_RSA();
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);
        $rsa->loadKey($this->getKey());

        return $rsa->verify($this->getData(), pack('H*', $signature));
    }
}
