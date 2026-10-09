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

        return view('wallet.deposit', compact('user', 'trc20Address', 'bep20Address'));
    }

    public function submitDeposit(Request $request)
    {
        $validated = $request->validate([
            'network' => ['required', 'in:TRC20,BEP20'],
            'amount_usd' => ['required', 'numeric', 'min:1'],
            'txid' => ['required', 'string', 'min:10', 'max:150'],
            'proof_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $walletAddress = match ($validated['network']) {
            'TRC20' => Setting::get('usdt_trc20_address', 'TYDzsYUEWzK1oP4vB7g8W8c4BvHnK7f8zM'),
            'BEP20' => Setting::get('usdt_bep20_address', '0x71C67Ed37037C0d7Bf3014B1F20606B5e4492A72'),
        };

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('deposits', 'public');
        }

        $depositCode = 'DEP-' . strtoupper(Str::random(8));

        CryptoDeposit::create([
            'deposit_code' => $depositCode,
            'user_id' => Auth::id(),
            'crypto_currency' => 'USDT',
            'network' => $validated['network'],
            'wallet_address' => $walletAddress,
            'amount_usd' => $validated['amount_usd'],
            'txid' => $validated['txid'],
            'proof_image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('wallet.index')
            ->with('success', "تم إرسال طلب شحن الرصيد برقم #{$depositCode}! سيتم تدقيق الحوالة وإضافة الرصيد إلى محفظتك خلال دقائق قليلة.");
    }
}
