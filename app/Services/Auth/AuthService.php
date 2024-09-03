<?php


namespace App\Services\Auth;


use App\Services\BaseService;
use App\Repositories\EmailVerificationRepository;
use App\Dtos\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use App\Models\User;
use App\Repositories\UserRepository;


class AuthService extends BaseService
{
    /**
     * @var EmailVerificationRepository
     */
    private EmailVerificationRepository $emailVerificationRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     * @param EmailVerificationRepository $emailVerificationRepository
     */
    public function __construct(UserRepository $userRepository, EmailVerificationRepository $emailVerificationCodeRepository)
    {
        $this->userRepository = $userRepository;
        $this->emailVerificationRepository = $emailVerificationCodeRepository;
    }

    /**
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function login(array $data): JsonResponse
    {
        /**
         * @var $model User
         */

        $model = $this->userRepository->findByEmailOrName($data['email']);

        if ($model and Hash::check($data['password'], $model->password))
        {
            return ApiResponse::Success([
                'type' => 'Bearer',
                'token' => $this->userRepository->createToken($data['email']),
                'user' => $model,
            ], TRUE);
        } else {
            return ApiResponse::Error("The provided username or password is incorrect.", Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @param array|string $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(array|string $data): JsonResponse
    {
        $EmailVerificationRepository = $this->emailVerificationRepository->findByEmail($data['email']);

        if ($EmailVerificationRepository and Hash::check($data['code'], $EmailVerificationRepository->code))
        {
            $data['password'] = bcrypt($data['password']);
            $data['roles'] = [['role_code' => 'new_user', 'status' => true]];
            $data['email_verified_at'] = date('Y-m-d');
            $user = $this->repository->create($data);
            $EmailVerificationRepository->delete($data['code']);

            return ApiResponse::Success([
                'type' => 'Bearer',
                'token' => $this->userRepository->createToken($data['email']),
                'user' => $user,
            ], TRUE);
        } else {
            return ApiResponse::Error("The email is not verified, please repeat again ", Response::HTTP_UNAUTHORIZED);
        }
    }


    /**
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function resetPassword(array $data): JsonResponse
    {
        $EmailVerificationRepository = $this->emailVerificationRepository->findByEmail($data['email']);

        if ($EmailVerificationRepository and Hash::check($data['code'], $EmailVerificationRepository->code))
        {

            $user = $this->userRepository->findByEmail($data['email']);
            $user->password = bcrypt($data['password']);
            $user->save();
            $EmailVerificationRepository->delete();

            return ApiResponse::Success([
                'type' => 'Bearer',
                'token' => $this->userRepository->createToken($data['email']),
                'user' => $user,
            ], TRUE);

        } else {
            return ApiResponse::Error("The email is not verified , please repeat again ", Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @return int
     * @throws Throwable
     */
    public function logout(): int
    {
        return $this->userRepository->removeToken(auth()->user());
    }
}
