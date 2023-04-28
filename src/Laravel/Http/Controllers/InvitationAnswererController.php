<?php /** @noinspection PhpUnused */
/** @noinspection PhpUndefinedNamespaceInspection */

/** @noinspection PhpUndefinedClassInspection */

namespace HichemtabTech\TokensValidation\Laravel\Http\Controllers;
use HichemtabTech\TokensValidation\TokensValidation;
use Illuminate\Http\Request;

class InvitationAnswererController extends Controller
{
    /** @noinspection PhpUndefinedFunctionInspection
     * @noinspection PhpInconsistentReturnPointsInspection
     * @noinspection LaravelUnknownViewInspection
     */
    public function index(Request $request)
    {
        $invitation = TokensValidation::checkInvitationUrl($request->getRequestUri());
        if ($invitation->isValidationSucceed()) {
            return view('invitation-answerer', compact('invitation'));
        }
        else{
            redirect("errors/invitation-invalid");
        }
    }
}