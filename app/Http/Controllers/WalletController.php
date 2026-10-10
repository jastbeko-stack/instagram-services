<?php

namespace App\Http\Controllers;

use App\Models\CryptoDeposit;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->paginate(15);
        $pendingDeposits = $user->cryptoDeposits()->where('status', 'pending')->get();

        return view('wallet.index', compact('user', 'transactions', 'pendingDeposits'));
    }

    public function depositForm()
    {
        $user = Auth::user();
        $trc20Address = Setting::get('usdt_trc20_address', 'TYDzsYUEWzK1oP4vB7g8W8c4BvHnK7f8zM');
        $bep20Address = Setting::get('usdt_bep20_address', '0x71C67Ed37037C0d7Bf3014B1F20606B5e4492A72');
        
        // Super Qi / Qi Card & ZainCash settings
        $qiCardNumber = Setting::get('qi_card_number', '9821 0000 1234 5678');
        $qiAccountName = Setting::get('qi_account_name', 'انستازون لخدمات الدفع');
        $zainCashNumber = Setting::get('zaincash_phone', '07800000000');
        $zainCashName = Setting::get('zaincash_account_name', 'InstaZone Official');
        $usdToIqdRate = (float) Setting::get('usd_to_iqd_rate', '1500'); // 1 USD = 1,500 IQD

        return view('wallet.deposit', compact(
            'user', 
            'trc20Address', 
            'bep20Address',
            'qiCardNumber',
            'qiAccountName',
            'zainCashNumber',
            'zainCashName',
            'usdToIqdRate'
        ));
    }

    public function submitDeposit(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:crypto,super_qi,zaincash,mastercard_manual'],
            'amount_usd' => ['required', 'numeric', 'min:1'],
            'amount_iqd' => ['nullable', 'numeric'],
            'txid' => ['nullable', 'string', 'max:150'],
            'card_last_four' => ['nullable', 'string', 'max:8'],
            'sender_phone' => ['nullable', 'string', 'max:30'],
            'proof_image' => ['nullable', 'image', 'max:5120'],
            // Crypto specific
            'network' => ['nullable', 'in:TRC20,BEP20'],
        ]);

        $paymentMethod = $validated['payment_method'];
        $walletAddress = null;
        $network = null;
        $cryptoCurrency = null;

        if ($paymentMethod === 'crypto') {
            $network = $request->input('network', 'TRC20');
            $cryptoCurrency = 'USDT';
            $walletAddress = match ($network) {
                'TRC20' => Setting::get('usdt_trc20_address', 'TYDzsYUEWzK1oP4vB7g8W8c4BvHnK7f8zM'),
                'BEP20' => Setting::get('usdt_bep20_address', '0x71C67Ed37037C0d7Bf3014B1F20606B5e4492A72'),
            };
            if (empty($validated['txid'])) {
                return back()->withErrors(['txid' => 'يرجى كتابة رمز المعاملة TXID للتحويل الرقمي.'])->withInput();
            }
        } elseif ($paymentMethod === 'super_qi') {
            $walletAddress = Setting::get('qi_card_number', '9821 0000 1234 5678');
        } elseif ($paymentMethod === 'zaincash') {
            $walletAddress = Setting::get('zaincash_phone', '07800000000');
        } elseif ($paymentMethod === 'mastercard_manual') {
            $walletAddress = Setting::get('qi_card_number', '9821 0000 1234 5678');
        }

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('deposits', 'public');
        }

        $depositCode = 'DEP-' . strtoupper(Str::random(8));

        CryptoDeposit::create([
            'deposit_code' => $depositCode,
            'user_id' => Auth::id(),
            'payment_method' => $paymentMethod,
            'crypto_currency' => $cryptoCurrency,
            'network' => $network,
            'wallet_address' => $walletAddress,
            'currency' => in_array($paymentMethod, ['super_qi', 'zaincash']) ? 'IQD' : 'USD',
            'amount_usd' => $validated['amount_usd'],
            'amount_iqd' => $validated['amount_iqd'] ?? null,
            'txid' => $validated['txid'] ?? null,
            'card_last_four' => $validated['card_last_four'] ?? null,
            'sender_phone' => $validated['sender_phone'] ?? null,
            'proof_image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('wallet.index')
            ->with('success', "تم إرسال طلب الشحن برقم #{$depositCode}! سيتم تدقيق الحوالة وإضافة الرصيد إلى حسابك فوراً.");
    }
}
